<?php

namespace Database\Seeders;

use App\Models\ArchitectType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArchitectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Residential',
            'Commercial',
            'Industrial',
            'Landscape',
            'Interior Design',
            'Urban Planning',
        ];

        foreach ($types as $type) {
            ArchitectType::firstOrCreate(['architect_type_desc' => $type]);
        }
    }
}
