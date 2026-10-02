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
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('isbn', 50)->unique();
            $table->string('author');
            $table->string('publisher');
            $table->integer('publication_year');
            $table->integer('total_stock')->default(1);
            $table->integer('available_stock')->default(1);
            $table->text('cover_image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            // Indexes for fast lookup
            $table->index('category_id');
            $table->index('isbn');
            $table->index('title');
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
