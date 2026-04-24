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
    Schema::create('cases', function (Blueprint $table) {
        $table->id();

        $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
        $table->foreignId('case_status_id')->constrained('case_statuses');

        // кто ведёт кейс (пользователь-координатор)
        $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();

        $table->string('title', 191)->nullable(); // кратко: "Второе мнение, рак молочной железы"
        $table->text('problem')->nullable();      // описание запроса/задачи
        $table->unsignedTinyInteger('priority')->default(3); // 1-5 (1 срочно)

        $table->timestamp('opened_at')->nullable();
        $table->timestamp('closed_at')->nullable();

        $table->timestamps();

        $table->index(['case_status_id', 'assigned_to_user_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_cases');
    }
};
