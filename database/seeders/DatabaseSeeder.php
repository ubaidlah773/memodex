<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CardTemplateSeeder::class,
            CardFrameSeeder::class,
        ]);

        if (!User::where('email', 'collector@memodex.test')->exists()) {
            User::factory()->create([
                'name' => 'Memodex Collector',
                'username' => 'collector',
                'email' => 'collector@memodex.test',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]);
        }

        if (!User::where('email', 'admin@memodex.test')->exists()) {
            User::factory()->create([
                'name' => 'Memodex Administrator',
                'username' => 'admin',
                'email' => 'admin@memodex.test',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]);
        }
    }
}
