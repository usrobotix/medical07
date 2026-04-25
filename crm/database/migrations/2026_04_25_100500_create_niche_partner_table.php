<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niche_partner', function (Blueprint $table) {
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->foreignId('niche_id')->constrained('niches')->cascadeOnDelete();
            $table->primary(['partner_id', 'niche_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niche_partner');
    }
};
