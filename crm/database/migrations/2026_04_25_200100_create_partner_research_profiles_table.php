<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_research_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->unique()->constrained('partners')->cascadeOnDelete();
            $table->json('key_services')->nullable();
            $table->json('accepts_foreigners')->nullable();
            $table->json('accepts_russians')->nullable();
            $table->json('working_hours')->nullable();
            $table->json('doctors')->nullable();
            $table->json('prices')->nullable();
            $table->json('payment_info')->nullable();
            $table->json('reviews')->nullable();
            $table->json('accreditations')->nullable();
            $table->json('sources')->nullable();
            $table->mediumText('review_md')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_research_profiles');
    }
};
