<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Card;
use App\Models\CardFrame;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemodexAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected User $userA;
    protected User $userB;
    protected User $adminUser;
    protected CardFrame $frame;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userA = User::factory()->create([
            'email' => 'usera@memodex.test',
            'is_admin' => false,
        ]);

        $this->userB = User::factory()->create([
            'email' => 'userb@memodex.test',
            'is_admin' => false,
        ]);

        $this->adminUser = User::factory()->create([
            'email' => 'admin@memodex.test',
            'is_admin' => true,
        ]);

        $this->frame = CardFrame::create([
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
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/pokedex');
        $response->assertRedirect('/login');

        $response = $this->get('/photos');
        $response->assertRedirect('/login');

        $response = $this->get('/albums');
        $response->assertRedirect('/login');

        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_user_a_cannot_view_user_b_photo(): void
    {
        $photoB = Photo::create([
            'user_id' => $this->userB->id,
            'original_name' => 'secret_photo.png',
            'original_path' => 'memodex/users/' . $this->userB->id . '/originals/secret.png',
            'mime_type' => 'image/png',
            'file_size' => 1024,
        ]);

        $response = $this->actingAs($this->userA)->get("/media/photos/{$photoB->id}/original");
        $response->assertForbidden();

        $response = $this->actingAs($this->userA)->get("/media/photos/{$photoB->id}/thumbnail");
        $response->assertForbidden();
    }

    public function test_user_a_cannot_view_or_download_user_b_card(): void
    {
        $photoB = Photo::create([
            'user_id' => $this->userB->id,
            'original_name' => 'photo.png',
            'original_path' => 'memodex/users/' . $this->userB->id . '/originals/photo.png',
            'mime_type' => 'image/png',
            'file_size' => 1024,
        ]);

        $cardB = Card::create([
            'user_id' => $this->userB->id,
            'photo_id' => $photoB->id,
            'frame_id' => $this->frame->id,
            'card_number' => 'MDX-000001',
            'name' => 'User B Secret Card',
        ]);

        $response = $this->actingAs($this->userA)->get("/pokedex/{$cardB->id}");
        $response->assertForbidden();

        $response = $this->actingAs($this->userA)->get("/media/cards/{$cardB->id}/download/framed");
        $response->assertForbidden();

        $response = $this->actingAs($this->userA)->get("/media/cards/{$cardB->id}/download/original");
        $response->assertForbidden();

        $response = $this->actingAs($this->userA)->get("/cards/{$cardB->id}/edit");
        $response->assertForbidden();
    }

    public function test_user_a_cannot_update_or_delete_user_b_card(): void
    {
        $photoB = Photo::create([
            'user_id' => $this->userB->id,
            'original_name' => 'photo.png',
            'original_path' => 'memodex/users/' . $this->userB->id . '/originals/photo.png',
            'mime_type' => 'image/png',
            'file_size' => 1024,
        ]);

        $cardB = Card::create([
            'user_id' => $this->userB->id,
            'photo_id' => $photoB->id,
            'frame_id' => $this->frame->id,
            'card_number' => 'MDX-000001',
            'name' => 'Card B',
        ]);

        $response = $this->actingAs($this->userA)->put("/cards/{$cardB->id}", [
            'name' => 'Hacked Card',
        ]);
        $response->assertForbidden();

        $response = $this->actingAs($this->userA)->delete("/cards/{$cardB->id}");
        $response->assertForbidden();
    }

    public function test_user_a_cannot_create_card_using_user_b_photo(): void
    {
        $photoB = Photo::create([
            'user_id' => $this->userB->id,
            'original_name' => 'photo_b.png',
            'original_path' => 'memodex/users/' . $this->userB->id . '/originals/photo_b.png',
            'mime_type' => 'image/png',
            'file_size' => 1024,
        ]);

        $response = $this->actingAs($this->userA)->post('/cards', [
            'photo_id' => $photoB->id,
            'frame_id' => $this->frame->id,
            'name' => 'Stolen Card',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('cards', [
            'user_id' => $this->userA->id,
            'name' => 'Stolen Card',
        ]);
    }

    public function test_user_a_cannot_view_or_modify_user_b_album(): void
    {
        $albumB = Album::create([
            'user_id' => $this->userB->id,
            'name' => 'Private Album B',
            'visibility' => 'private',
        ]);

        $response = $this->actingAs($this->userA)->get("/albums/{$albumB->id}");
        $response->assertForbidden();

        $response = $this->actingAs($this->userA)->delete("/albums/{$albumB->id}");
        $response->assertForbidden();
    }

    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $response = $this->actingAs($this->userA)->get('/admin');
        $response->assertForbidden();

        $response = $this->actingAs($this->userA)->get('/admin/frames');
        $response->assertForbidden();

        $response = $this->actingAs($this->userA)->get('/admin/users');
        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_panel_and_toggle_frames(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin');
        $response->assertOk();

        $response = $this->actingAs($this->adminUser)->get('/admin/frames');
        $response->assertOk();

        $response = $this->actingAs($this->adminUser)->get('/admin/users');
        $response->assertOk();

        // Toggle frame active status
        $this->assertTrue($this->frame->is_active);
        $toggleResponse = $this->actingAs($this->adminUser)->post("/admin/frames/{$this->frame->id}/toggle");
        $toggleResponse->assertRedirect(route('admin.frames.index'));
        $this->assertFalse($this->frame->fresh()->is_active);
    }
}
