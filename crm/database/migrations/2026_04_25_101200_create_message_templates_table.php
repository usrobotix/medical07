<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->unique();
            $table->string('title', 191);
            // email | whatsapp | telegram
            $table->string('channel', 32)->index();
            // ru | en
            $table->string('language', 8)->default('ru');
            $table->string('subject', 255)->nullable();
            $table->text('body');
            // clinic | translator | curator | null
            $table->string('target_partner_type', 32)->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_templates');
    }
};
