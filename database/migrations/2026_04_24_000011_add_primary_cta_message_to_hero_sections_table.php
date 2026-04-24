<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            $table->text('primary_cta_message')->nullable()->after('primary_cta_url');
        });

        DB::table('hero_sections')
            ->select(['id', 'primary_cta_url'])
            ->orderBy('id')
            ->get()
            ->each(function (object $heroSection): void {
                if (! $heroSection->primary_cta_url || ! Str::contains($heroSection->primary_cta_url, 'wa.me/')) {
                    return;
                }

                parse_str((string) parse_url($heroSection->primary_cta_url, PHP_URL_QUERY), $query);

                DB::table('hero_sections')
                    ->where('id', $heroSection->id)
                    ->update([
                        'primary_cta_url' => strtok($heroSection->primary_cta_url, '?') ?: $heroSection->primary_cta_url,
                        'primary_cta_message' => trim((string) ($query['text'] ?? '')) ?: null,
                    ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hero_sections')
            ->select(['id', 'primary_cta_url', 'primary_cta_message'])
            ->orderBy('id')
            ->get()
            ->each(function (object $heroSection): void {
                if (! $heroSection->primary_cta_url || ! $heroSection->primary_cta_message || ! Str::contains($heroSection->primary_cta_url, 'wa.me/')) {
                    return;
                }

                DB::table('hero_sections')
                    ->where('id', $heroSection->id)
                    ->update([
                        'primary_cta_url' => $heroSection->primary_cta_url.'?text='.rawurlencode($heroSection->primary_cta_message),
                    ]);
            });

        Schema::table('hero_sections', function (Blueprint $table) {
            $table->dropColumn('primary_cta_message');
        });
    }
};
