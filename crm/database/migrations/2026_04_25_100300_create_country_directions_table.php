<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('country_directions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->foreignId('niche_id')->nullable()->constrained('niches')->nullOnDelete();
            $table->string('title', 191);
            $table->text('what_to_look_for')->nullable();
            $table->text('search_queries')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->timestamps();

            $table->index(['country_id', 'niche_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_directions');
    }
};
