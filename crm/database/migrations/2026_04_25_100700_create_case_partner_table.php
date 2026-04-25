<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_partner', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_case_id')->constrained('cases')->cascadeOnDelete();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->string('role', 64)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['medical_case_id', 'partner_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_partner');
    }
};
