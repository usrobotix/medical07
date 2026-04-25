<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('address', 500)->nullable()->after('city');
            $table->string('international_page_url', 500)->nullable()->after('website_url');
            $table->string('research_slug', 255)->unique()->nullable()->after('notes');
            $table->string('research_source_path', 500)->nullable()->after('research_slug');
            $table->timestamp('research_imported_at')->nullable()->after('research_source_path');
            $table->date('last_checked_date')->nullable()->after('research_imported_at');
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'international_page_url',
                'research_slug',
                'research_source_path',
                'research_imported_at',
                'last_checked_date',
            ]);
        });
    }
};
