<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->index()->constrained('trainings')->cascadeOnDelete();
            $table->enum('type', ['pre_test', 'post_test'])->index();
            $table->string('title', 200);
            $table->text('instruction')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->decimal('passing_grade', 5, 2)->nullable();
            $table->unsignedInteger('max_attempts')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['training_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
