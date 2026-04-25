<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_id')->constrained('verification_checklists')->cascadeOnDelete();
            $table->string('code', 64);
            $table->text('text');
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->timestamps();

            $table->unique(['checklist_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_checklist_items');
    }
};
