<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->boolean('approved')->default(false);
            $table->double('funds')->default(00.00);

            $table->foreignId('owner_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('country_id')
                  ->nullable()
                  ->constrained('countries')
                  ->nullOnDelete();

            $table->foreignId('state_id')
                  ->nullable()
                  ->constrained('states')
                  ->nullOnDelete();

            $table->foreignId('city_id')
                  ->nullable()
                  ->constrained('cities')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
