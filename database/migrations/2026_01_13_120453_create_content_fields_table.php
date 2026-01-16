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
        Schema::create('content_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_type_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Field identifier: "title", "body", "author"
            $table->string('display_name'); // Human readable: "Title", "Body", "Author"
            $table->string('type'); // Field type: string, text, rich_text, number, boolean, date, media, json, relation
            $table->text('description')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_unique')->default(false);
            $table->boolean('is_translatable')->default(false);
            $table->integer('order')->default(0); // Display order
            $table->json('validation_rules')->nullable(); // Additional Laravel validation rules
            $table->json('settings')->nullable(); // Field-specific settings (e.g., max_length, options for select)
            $table->timestamps();
            
            // Unique constraint: one field name per content type
            $table->unique(['content_type_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_fields');
    }
};