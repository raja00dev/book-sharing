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
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->text('description')->nullable();

            $table->foreignId('author_id')
                ->constrained()
                ->restrictOnDelete();

            $table->decimal('price', 12, 2);

            $table->unsignedInteger('stock')->default(0);

            $table->string('cover_image')->nullable();

            $table->string('publisher')->nullable();

            $table->unsignedSmallInteger('publication_year')->nullable();

            $table->unsignedInteger('pages')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
