<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_statuses', function (Blueprint $table) {
            $table->id();

            // Порядок воронки (1..N). Для служебных статусов тоже зададим порядок (например 100+)
            $table->unsignedSmallInteger('sort_order')->index();

            // Уникальный "ключ" статуса (для кода)
            $table->string('code', 64)->unique();

            // Человекочитаемое имя
            $table->string('name', 191);

            // Доп. описание (как в status.md пункты)
            $table->text('description')->nullable();

            // Воронка/служебный
            $table->boolean('is_service')->default(false)->index();

            // Финальные статусы (успешно/неуспешно)
            $table->boolean('is_terminal')->default(false)->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_statuses');
    }
};