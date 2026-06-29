<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->index()->constrained('trainings')->cascadeOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->enum('type', ['file', 'link'])->index();
            $table->string('file_path')->nullable();
            $table->string('file_type', 50)->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_downloadable')->default(true);
            $table->boolean('is_required')->default(true);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_materials');
    }
};
