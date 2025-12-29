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
        // Check if slug column exists
        if (!Schema::hasColumn('ads', 'slug')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('title');
            });
        }
        
        // Generate unique slugs for existing ads
        $usedSlugs = [];
        \App\Models\Ad::chunk(100, function ($ads) use (&$usedSlugs) {
            foreach ($ads as $ad) {
                if (!$ad->slug) {
                    $baseSlug = \Illuminate\Support\Str::slug($ad->title);
                    $slug = $baseSlug;
                    $counter = 1;
                    
                    // Make sure slug is unique
                    while (in_array($slug, $usedSlugs) || \App\Models\Ad::where('slug', $slug)->where('id', '!=', $ad->id)->exists()) {
                        $slug = $baseSlug . '-' . $counter;
                        $counter++;
                    }
                    
                    $usedSlugs[] = $slug;
                    $ad->slug = $slug;
                    $ad->save();
                } else {
                    $usedSlugs[] = $ad->slug;
                }
            }
        });
        
        // Add unique constraint using raw SQL to avoid errors
        $connection = Schema::getConnection();
        $indexes = $connection->select("SHOW INDEXES FROM ads WHERE Key_name = 'ads_slug_unique'");
        if (empty($indexes)) {
            try {
                $connection->statement("ALTER TABLE ads ADD UNIQUE INDEX ads_slug_unique (slug)");
            } catch (\Exception $e) {
                // Ignore if already exists
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }
};
