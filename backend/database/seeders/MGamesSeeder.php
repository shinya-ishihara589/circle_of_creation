<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MGamesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $games = [
            ['id' => 1, 'name' => 'ナンバータッチゲーム', 'is_active' => true],
            ['id' => 2, 'name' => '不等号ゲーム', 'is_active' => true],
        ];

        foreach ($games as $game) {
            DB::table('m_games')->updateOrInsert(
                ['id' => $game['id']],
                ['name' => $game['name'], 'is_active' => $game['is_active'], 'updated_at' => now()],
            );
        }
    }
}
