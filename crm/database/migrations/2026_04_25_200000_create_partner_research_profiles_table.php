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
            $table->text('address')->nullable();
            $table->string('direction', 100)->nullable();
            $table->json('key_services')->nullable();
            $table->text('international_page_url')->nullable();
            $table->string('accepts_foreigners_status', 32)->nullable();
            $table->text('accepts_foreigners_source_url')->nullable();
            $table->string('accepts_ru_status', 32)->nullable();
            $table->text('accepts_ru_source_url')->nullable();
            $table->text('working_hours')->nullable();
            $table->json('doctors')->nullable();
            $table->json('prices')->nullable();
            $table->json('reviews')->nullable();
            $table->json('sources')->nullable();
            $table->date('last_checked_at')->nullable();
            $table->string('source_path', 500)->nullable();
            $table->longText('raw_clinic_yaml')->nullable();
            $table->longText('review_markdown')->nullable();
            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_research_profiles');
    }
};
