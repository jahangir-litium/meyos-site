<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@meyos.uz'],
            [
                'name'     => 'MEYOS Admin',
                'password' => bcrypt('meyos-admin-2026'),
            ],
        );

        $this->call(MeyosContentSeeder::class);
    }
}
