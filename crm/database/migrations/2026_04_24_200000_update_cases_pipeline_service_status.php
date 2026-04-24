<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            // Drop old index and foreign key on case_status_id
            $table->dropForeign(['case_status_id']);
            $table->dropIndex(['case_status_id', 'assigned_to_user_id']);

            // Rename case_status_id → pipeline_status_id
            $table->renameColumn('case_status_id', 'pipeline_status_id');
        });

        Schema::table('cases', function (Blueprint $table) {
            // Add foreign key on renamed column
            $table->foreign('pipeline_status_id')->references('id')->on('case_statuses');

            // Add nullable service_status_id overlay
            $table->foreignId('service_status_id')
                ->nullable()
                ->after('pipeline_status_id')
                ->constrained('case_statuses')
                ->nullOnDelete();

            // Restore indexes
            $table->index(['pipeline_status_id', 'assigned_to_user_id']);
            $table->index('service_status_id');
        });
    }

    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropForeign(['service_status_id']);
            $table->dropIndex(['service_status_id']);
            $table->dropColumn('service_status_id');

            $table->dropForeign(['pipeline_status_id']);
            $table->dropIndex(['pipeline_status_id', 'assigned_to_user_id']);

            $table->renameColumn('pipeline_status_id', 'case_status_id');
        });

        Schema::table('cases', function (Blueprint $table) {
            $table->foreign('case_status_id')->references('id')->on('case_statuses');
            $table->index(['case_status_id', 'assigned_to_user_id']);
        });
    }
};
