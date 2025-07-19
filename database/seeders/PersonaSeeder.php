<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $personas = [
            [
                'name' => 'Happy',
                'description' => 'A cheerful and optimistic persona.',
                'image' => 'images/happy.png',
                'unlock_condition' => 'default',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sad',
                'description' => 'A calm and reflective persona.',
                'image' => 'images/sad.png',
                'unlock_condition' => 'default',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Angry',
                'description' => 'A fiery and intense persona.',
                'image' => 'images/angry.png',
                'unlock_condition' => 'default',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Relaxed',
                'description' => 'A peaceful and easygoing persona.',
                'image' => 'images/relaxed.png',
                'unlock_condition' => 'default',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Energetic',
                'description' => 'A lively and enthusiastic persona.',
                'image' => 'images/energetic.png',
                'unlock_condition' => 'default',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Persona::insert($personas);
    }
}