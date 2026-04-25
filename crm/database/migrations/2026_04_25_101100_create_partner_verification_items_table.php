<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_verification_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_verification_id')->constrained('partner_verifications')->cascadeOnDelete();
            $table->foreignId('checklist_item_id')->constrained('verification_checklist_items')->cascadeOnDelete();
            $table->boolean('is_checked')->default(false);
            $table->timestamp('checked_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['partner_verification_id', 'checklist_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_verification_items');
    }
};
