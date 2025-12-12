<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_seminars_table.php
    public function up(): void
    {
        Schema::create('seminars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('thumbnail')->nullable();
            $table->string('location');
            $table->dateTime('date_start');
            $table->integer('quota')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->timestamps();

            // Index untuk pencarian cepat
            $table->index(['status', 'date_start']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminars');
    }
};
