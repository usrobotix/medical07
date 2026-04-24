<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_status_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('medical_case_id')
                ->constrained('cases')
                ->cascadeOnDelete();

            $table->foreignId('from_pipeline_status_id')
                ->nullable()
                ->constrained('case_statuses')
                ->nullOnDelete();

            $table->foreignId('to_pipeline_status_id')
                ->nullable()
                ->constrained('case_statuses')
                ->nullOnDelete();

            $table->foreignId('from_service_status_id')
                ->nullable()
                ->constrained('case_statuses')
                ->nullOnDelete();

            $table->foreignId('to_service_status_id')
                ->nullable()
                ->constrained('case_statuses')
                ->nullOnDelete();

            $table->foreignId('changed_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // pipeline | service
            $table->string('change_type', 20)->default('pipeline');

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index('medical_case_id');
            $table->index('changed_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_status_histories');
    }
};
