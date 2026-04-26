<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('backup_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(true);
            $table->string('schedule_time')->default('03:00'); // HH:MM
            $table->string('backup_type')->default('full'); // db, files, full
            $table->string('file_preset')->default('project'); // project, storage_app
            $table->json('formats')->default('["zip","tar_gz"]');
            $table->boolean('upload_yandex')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('backup_settings'); }
};
