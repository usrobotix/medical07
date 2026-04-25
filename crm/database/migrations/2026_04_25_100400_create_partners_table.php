<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_layer_id')->constrained('partner_layers');
            // clinic | translator | curator
            $table->string('type', 32);
            $table->string('name', 191);
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->string('city', 191)->nullable();
            $table->string('languages', 191)->nullable();
            $table->string('contact_name', 191)->nullable();
            $table->string('contact_email', 191)->nullable();
            $table->string('contact_phone', 64)->nullable();
            $table->string('contact_whatsapp', 64)->nullable();
            $table->string('contact_telegram', 64)->nullable();
            $table->string('website_url', 255)->nullable();
            $table->unsignedSmallInteger('sla_response_hours')->nullable();
            $table->unsignedSmallInteger('sla_result_days')->nullable();
            $table->text('pricing_notes')->nullable();
            $table->boolean('invoice_required')->default(false);
            // new | verified | active | frozen
            $table->string('status', 32)->default('new')->index();
            $table->unsignedSmallInteger('verification_score')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
