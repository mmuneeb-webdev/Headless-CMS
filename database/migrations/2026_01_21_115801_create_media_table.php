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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('filename'); // Original filename
            $table->string('disk')->default('public'); // Storage disk
            $table->string('path'); // Storage path
            $table->string('mime_type'); // File MIME type
            $table->bigInteger('size'); // File size in bytes
            $table->string('extension', 10); // File extension
            $table->integer('width')->nullable(); // Image width
            $table->integer('height')->nullable(); // Image height
            $table->string('alt_text')->nullable(); // SEO alt text
            $table->text('caption')->nullable(); // Image caption
            $table->string('folder')->nullable(); // Optional folder organization
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('uploaded_by');
            $table->index('mime_type');
            $table->index('folder');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};