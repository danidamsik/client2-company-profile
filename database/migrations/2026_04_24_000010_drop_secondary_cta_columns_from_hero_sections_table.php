<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            if (Schema::hasColumn('hero_sections', 'secondary_cta_label')) {
                $table->dropColumn('secondary_cta_label');
            }

            if (Schema::hasColumn('hero_sections', 'secondary_cta_url')) {
                $table->dropColumn('secondary_cta_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            if (! Schema::hasColumn('hero_sections', 'secondary_cta_label')) {
                $table->string('secondary_cta_label')->nullable();
            }

            if (! Schema::hasColumn('hero_sections', 'secondary_cta_url')) {
                $table->string('secondary_cta_url')->nullable();
            }
        });
    }
};
