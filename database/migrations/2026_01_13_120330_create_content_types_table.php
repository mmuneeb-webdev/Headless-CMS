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
        Schema::create('content_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "blog-post", "product"
            $table->string('display_name'); // e.g., "Blog Post", "Product"
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // Icon class for UI
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Additional settings
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_types');
    }
};