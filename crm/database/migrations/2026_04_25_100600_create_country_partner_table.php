<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('country_partner', function (Blueprint $table) {
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->primary(['partner_id', 'country_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_partner');
    }
};
