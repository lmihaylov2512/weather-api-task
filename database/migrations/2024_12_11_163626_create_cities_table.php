<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{Schema, Artisan};
use Database\Seeders\CitySeeder;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('name', 100)->nullable(false)->unique('idx_cities_name');
            $table->timestamps();
        });

        // ensuring the sample cities seeder will be executed only once
        Artisan::call('db:seed', [
            '--class' => CitySeeder::class,
            '--force' => 'yes',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
