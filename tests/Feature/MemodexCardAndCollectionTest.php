<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Card;
use App\Models\CardFrame;
use App\Models\Photo;
use App\Models\SharedLink;
use App\Models\User;
use App\Rendering\CardFrameRenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MemodexCardAndCollectionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected CardFrame $classicFrame;
    protected CardFrame $goldFrame;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');

        $this->user = User::factory()->create([
            'email' => 'collector@memodex.test',
            'storage_quota_bytes' => 524288000, // 500MB
            'storage_used_bytes' => 0,
        ]);

        $this->classicFrame = CardFrame::create([
            'name' => 'Classic Frame',
            'slug' => 'classic',
            'description' => 'Classic obsidian frame.',
            'config' => [
                'theme' => 'classic',
                'outer_bg' => '#0F1622',
                'border_primary' => '#3D4F66',
                'border_inner' => '#202E40',
                'accent_color' => '#83B9F2',
                'border_width' => 32,
                'inner_padding' => 12,
                'label' => 'CLASSIC ARCHIVE',
            ],
            'is_active' => true,
        ]);

        $this->goldFrame = CardFrame::create([
            'name' => 'Gold Frame',
            'slug' => 'gold',
            'description' => 'Metallic gold frame.',
            'config' => [
                'theme' => 'gold',
                'outer_bg' => '#1A160E',
                'border_primary' => '#D4AF37',
                'border_inner' => '#AA771C',
                'accent_color' => '#F3E5AB',
                'border_width' => 32,
                'inner_padding' => 14,
                'label' => 'GOLD MASTER',
            ],
            'is_active' => true,
        ]);
    }

    public function test_photo_upload_validates_file_type_and_size(): void
    {
        $invalidFile = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->user)->post('/photos', [
            'photos' => [$invalidFile],
        ]);
        $response->assertSessionHasErrors('photos.0');

        $validImage = UploadedFile::fake()->image('test_photo.jpg', 800, 600);
        $response = $this->actingAs($this->user)->post('/photos', [
            'photos' => [$validImage],
        ]);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('photos', [
            'user_id' => $this->user->id,
            'original_name' => 'test_photo.jpg',
        ]);
    }

    public function test_storage_quota_prevents_uploads_beyond_limit(): void
    {
        // Set user storage limit to tiny 100 bytes
        $this->user->update([
            'storage_quota_bytes' => 100,
            'storage_used_bytes' => 90,
        ]);

        $image = UploadedFile::fake()->image('large.png', 400, 400)->size(500); // 500 KB > 10 bytes left

        $response = $this->actingAs($this->user)->post('/photos', [
            'photos' => [$image],
        ]);

        $response->assertSessionHas('warning');
        $this->assertDatabaseMissing('photos', [
            'user_id' => $this->user->id,
            'original_name' => 'large.png',
        ]);
    }

    public function test_user_can_create_card_from_owned_photo_with_sequential_number_and_preserved_original(): void
    {
        $image = UploadedFile::fake()->image('pikachu_card.png', 630, 880);
        $this->actingAs($this->user)->post('/photos', ['photos' => [$image]]);
        $photo = Photo::where('user_id', $this->user->id)->firstOrFail();

        $originalBytesBefore = Storage::disk('local')->get($photo->original_path);

        $response = $this->actingAs($this->user)->post('/cards', [
            'photo_id' => $photo->id,
            'frame_id' => $this->classicFrame->id,
            'name' => 'Pikachu Special',
        ]);

        $card = Card::where('user_id', $this->user->id)->first();
        $this->assertNotNull($card);
        $this->assertEquals('MDX-000001', $card->card_number);
        $this->assertEquals('Pikachu Special', $card->name);
        $this->assertNotNull($card->framed_image_path);

        // Verify original photo file is 100% UNTOUCHED and identical in bytes
        $originalBytesAfter = Storage::disk('local')->get($photo->original_path);
        $this->assertEquals($originalBytesBefore, $originalBytesAfter, 'Original card file must remain completely unaltered');

        // Verify framed composite exists as a distinct file
        $this->assertTrue(Storage::disk('local')->exists($card->framed_image_path));
        $this->assertNotEquals($photo->original_path, $card->framed_image_path);

        $response->assertRedirect(route('pokedex.show', $card));

        // Create second card to verify sequential number MDX-000002
        $this->actingAs($this->user)->post('/cards', [
            'photo_id' => $photo->id,
            'frame_id' => $this->goldFrame->id,
            'name' => 'Pikachu Gold Edition',
        ]);

        $secondCard = Card::where('user_id', $this->user->id)->orderByDesc('id')->first();
        $this->assertEquals('MDX-000002', $secondCard->card_number);
    }

    public function test_user_can_switch_frame_on_archived_card(): void
    {
        $image = UploadedFile::fake()->image('charizard.png', 630, 880);
        $this->actingAs($this->user)->post('/photos', ['photos' => [$image]]);
        $photo = Photo::where('user_id', $this->user->id)->firstOrFail();

        $this->actingAs($this->user)->post('/cards', [
            'photo_id' => $photo->id,
            'frame_id' => $this->classicFrame->id,
            'name' => 'Charizard Holo',
        ]);

        $card = Card::where('user_id', $this->user->id)->firstOrFail();
        $this->assertEquals($this->classicFrame->id, $card->frame_id);
        $firstFramedPath = $card->framed_image_path;

        // Switch frame to Gold Frame
        $response = $this->actingAs($this->user)->post("/cards/{$card->id}/frame", [
            'frame_id' => $this->goldFrame->id,
        ]);

        $response->assertRedirect(route('pokedex.show', $card));
        $card->refresh();
        $this->assertEquals($this->goldFrame->id, $card->frame_id);
        $this->assertTrue(Storage::disk('local')->exists($card->framed_image_path));
    }

    public function test_automatic_batch_mode_creates_multiple_cards(): void
    {
        $file1 = UploadedFile::fake()->image('card1.png', 630, 880);
        $file2 = UploadedFile::fake()->image('card2.png', 630, 880);

        $response = $this->actingAs($this->user)->post('/cards', [
            'photos' => [$file1, $file2],
            'frame_id' => $this->classicFrame->id,
        ]);

        $response->assertRedirect(route('pokedex.index'));
        $this->assertCount(2, $this->user->cards);
        $cards = $this->user->cards()->orderBy('id')->get();
        $this->assertEquals('MDX-000001', $cards[0]->card_number);
        $this->assertEquals('MDX-000002', $cards[1]->card_number);
    }

    public function test_separate_downloads_for_framed_and_original(): void
    {
        $image = UploadedFile::fake()->image('card_dl.png', 630, 880);
        $this->actingAs($this->user)->post('/photos', ['photos' => [$image]]);
        $photo = Photo::where('user_id', $this->user->id)->firstOrFail();

        $this->actingAs($this->user)->post('/cards', [
            'photo_id' => $photo->id,
            'frame_id' => $this->classicFrame->id,
            'name' => 'Downloadable Card',
        ]);

        $card = Card::where('user_id', $this->user->id)->firstOrFail();

        // Download framed
        $responseFramed = $this->actingAs($this->user)->get("/media/cards/{$card->id}/download/framed");
        $responseFramed->assertOk();

        // Download original
        $responseOrig = $this->actingAs($this->user)->get("/media/cards/{$card->id}/download/original");
        $responseOrig->assertOk();
    }

    public function test_one_card_can_belong_to_multiple_albums(): void
    {
        $photo = Photo::create([
            'user_id' => $this->user->id,
            'original_name' => 'p.jpg',
            'original_path' => 'memodex/users/' . $this->user->id . '/originals/p.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 1024,
        ]);

        $card = Card::create([
            'user_id' => $this->user->id,
            'photo_id' => $photo->id,
            'frame_id' => $this->classicFrame->id,
            'card_number' => 'MDX-000001',
            'name' => 'Cross Album Card',
        ]);

        $album1 = Album::create(['user_id' => $this->user->id, 'name' => 'Travels 2024']);
        $album2 = Album::create(['user_id' => $this->user->id, 'name' => 'Family Favorites']);

        $this->actingAs($this->user)->post("/albums/{$album1->id}/cards", ['card_id' => $card->id]);
        $this->actingAs($this->user)->post("/albums/{$album2->id}/cards", ['card_id' => $card->id]);

        $this->assertCount(2, $card->fresh()->albums);
        $this->assertTrue($album1->cards()->where('card_id', $card->id)->exists());
        $this->assertTrue($album2->cards()->where('card_id', $card->id)->exists());
    }

    public function test_deleting_photo_with_dependent_cards_is_blocked(): void
    {
        $photo = Photo::create([
            'user_id' => $this->user->id,
            'original_name' => 'referenced.jpg',
            'original_path' => 'memodex/users/' . $this->user->id . '/originals/ref.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => 1024,
        ]);

        Card::create([
            'user_id' => $this->user->id,
            'photo_id' => $photo->id,
            'frame_id' => $this->classicFrame->id,
            'card_number' => 'MDX-000001',
            'name' => 'Dependent Card',
        ]);

        $response = $this->actingAs($this->user)->delete("/photos/{$photo->id}");
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('photos', ['id' => $photo->id]);
    }

    public function test_shared_link_exposes_only_linked_album_and_denies_revoked_tokens(): void
    {
        $album = Album::create([
            'user_id' => $this->user->id,
            'name' => 'Public Showcase',
            'description' => 'Selected trading cards for friends.',
            'visibility' => 'private',
        ]);

        $this->actingAs($this->user)->post("/albums/{$album->id}/share");
        $sharedLink = SharedLink::where('album_id', $album->id)->firstOrFail();

        // Guest can view public shared album
        $response = $this->get("/shared/{$sharedLink->token}");
        $response->assertOk();
        $response->assertSee('Public Showcase');

        // Revoke token
        $this->actingAs($this->user)->delete("/albums/{$album->id}/share/{$sharedLink->id}");

        // Now viewing the token must be 404
        $revokedResponse = $this->get("/shared/{$sharedLink->token}");
        $revokedResponse->assertNotFound();
    }

    public function test_card_frame_renderer_creates_file_at_expected_dimensions(): void
    {
        $photo = Photo::create([
            'user_id' => $this->user->id,
            'original_name' => 'art.png',
            'original_path' => 'memodex/users/' . $this->user->id . '/originals/art.png',
            'mime_type' => 'image/png',
            'file_size' => 1024,
        ]);

        $card = Card::create([
            'user_id' => $this->user->id,
            'photo_id' => $photo->id,
            'frame_id' => $this->classicFrame->id,
            'card_number' => 'MDX-000001',
            'name' => 'Render Test Card',
        ]);

        $renderer = app(CardFrameRenderer::class);
        $renderRelPath = $renderer->renderFramedCard($card, $this->classicFrame);

        $absPath = Storage::disk('local')->path($renderRelPath);
        $this->assertFileExists($absPath);

        $info = getimagesize($absPath);
        $this->assertEquals(744, $info[0], 'Card width must be exactly 744 px (300 DPI 63x88mm ratio)');
        $this->assertEquals(1039, $info[1], 'Card height must be exactly 1039 px');
        $this->assertEquals(IMAGETYPE_PNG, $info[2]);
    }
}
