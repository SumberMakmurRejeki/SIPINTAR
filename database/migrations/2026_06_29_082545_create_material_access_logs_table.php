<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_material_id')->index()->constrained('training_materials')->cascadeOnDelete();
            $table->foreignId('employee_id')->index()->constrained('employees')->cascadeOnDelete();
            $table->foreignId('training_participant_id')->index()->nullable()->constrained('training_participants')->cascadeOnDelete();
            $table->timestamp('accessed_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_access_logs');
    }
};
