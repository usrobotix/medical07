<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->foreignId('checklist_id')->constrained('verification_checklists');
            // not_started | in_progress | passed | failed
            $table->string('status', 32)->default('not_started')->index();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['partner_id', 'checklist_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_verifications');
    }
};
