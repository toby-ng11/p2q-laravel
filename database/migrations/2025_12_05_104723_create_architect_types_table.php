<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('architect_types', function (Blueprint $table) {
            $table->id();
            $table->string('architect_type_desc', 50);
        });

        $architectTypes = [
            'Residential',
            'Commercial',
            'Industrial',
            'Landscape',
            'Interior Design',
            'Urban Planning',
        ];

        foreach ($architectTypes as $type) {
            DB::table('architect_types')->insert([
                'architect_type_desc' => $type,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('architect_types');
    }
};
