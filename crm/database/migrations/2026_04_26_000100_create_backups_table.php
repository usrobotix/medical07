<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'db', 'files', 'full'
            $table->string('file_preset')->nullable(); // 'project', 'storage_app', 'user_files'
            $table->json('formats'); // ['zip', 'tar_gz']
            $table->json('local_paths')->nullable(); // {zip: 'path', tar_gz: 'path'}
            $table->json('remote_paths')->nullable(); // {zip: 'url', tar_gz: 'url'}
            $table->bigInteger('size_bytes')->default(0);
            $table->string('status')->default('pending'); // pending, running, done, failed
            $table->text('error_message')->nullable();
            $table->string('initiated_by')->default('user'); // 'user', 'cron'
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('backups'); }
};
