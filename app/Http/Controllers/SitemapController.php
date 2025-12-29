<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        try {
            $baseUrl = url('/');
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $sitemap .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            $sitemap .= '  <sitemap>' . "\n";
            $sitemap .= '    <loc>' . htmlspecialchars($baseUrl . '/sitemap-pages.xml', ENT_XML1, 'UTF-8') . '</loc>' . "\n";
            $sitemap .= '    <lastmod>' . htmlspecialchars(now()->toAtomString(), ENT_XML1, 'UTF-8') . '</lastmod>' . "\n";
            $sitemap .= '  </sitemap>' . "\n";
            $sitemap .= '  <sitemap>' . "\n";
            $sitemap .= '    <loc>' . htmlspecialchars($baseUrl . '/sitemap-ads.xml', ENT_XML1, 'UTF-8') . '</loc>' . "\n";
            $sitemap .= '    <lastmod>' . htmlspecialchars(now()->toAtomString(), ENT_XML1, 'UTF-8') . '</lastmod>' . "\n";
            $sitemap .= '  </sitemap>' . "\n";
            $sitemap .= '  <sitemap>' . "\n";
            $sitemap .= '    <loc>' . htmlspecialchars($baseUrl . '/sitemap-blog.xml', ENT_XML1, 'UTF-8') . '</loc>' . "\n";
            $sitemap .= '    <lastmod>' . htmlspecialchars(now()->toAtomString(), ENT_XML1, 'UTF-8') . '</lastmod>' . "\n";
            $sitemap .= '  </sitemap>' . "\n";
            $sitemap .= '</sitemapindex>';

            return response($sitemap, 200)
                ->header('Content-Type', 'application/xml; charset=utf-8')
                ->header('Cache-Control', 'public, max-age=3600');
        } catch (\Exception $e) {
            // Return minimal valid XML on error
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $sitemap .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            $sitemap .= '</sitemapindex>';
            
            return response($sitemap, 200)
                ->header('Content-Type', 'application/xml; charset=utf-8');
        }
    }

    public function pages()
    {
        $urls = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => url('/store'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => url('/auctions'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => url('/blog'), 'priority' => '0.8', 'changefreq' => 'daily'],
        ];

        return $this->generateSitemap($urls);
    }

    public function ads()
    {
        try {
            // فقط آگهی‌هایی که منقضی نشده‌اند در sitemap قرار می‌گیرند
            $ads = Ad::where('status', 'active')
                ->where('is_active', true)
                ->where(function($q) {
                    // آگهی‌هایی که expire_at ندارند یا expire_at آنها در آینده است
                    $q->whereNull('expire_at')
                      ->orWhere('expire_at', '>', now());
                })
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->latest()
                ->get();

            $urls = $ads->map(function ($ad) {
                try {
                    return [
                        'loc' => route('store.show', $ad->slug),
                        'lastmod' => $ad->updated_at ? $ad->updated_at->toAtomString() : now()->toAtomString(),
                        'priority' => '0.7',
                        'changefreq' => 'weekly',
                    ];
                } catch (\Exception $e) {
                    // Skip ads that can't generate route
                    return null;
                }
            })->filter()->toArray();

            return $this->generateSitemap($urls);
        } catch (\Exception $e) {
            // Return empty sitemap if there's an error
            return $this->generateSitemap([]);
        }
    }

    public function blog()
    {
        try {
            $posts = Post::published()
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->latest('published_at')
                ->get();

            $urls = $posts->map(function ($post) {
                try {
                    return [
                        'loc' => route('blog.post', $post->slug),
                        'lastmod' => $post->updated_at ? $post->updated_at->toAtomString() : now()->toAtomString(),
                        'priority' => '0.6',
                        'changefreq' => 'monthly',
                    ];
                } catch (\Exception $e) {
                    // Skip posts that can't generate route
                    return null;
                }
            })->filter()->toArray();

            return $this->generateSitemap($urls);
        } catch (\Exception $e) {
            // Return empty sitemap if there's an error
            return $this->generateSitemap([]);
        }
    }

    private function generateSitemap(array $urls)
    {
        try {
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

            foreach ($urls as $url) {
                if (!isset($url['loc']) || empty($url['loc'])) {
                    continue; // Skip invalid URLs
                }

                $sitemap .= '  <url>' . "\n";
                $sitemap .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1, 'UTF-8') . '</loc>' . "\n";
                if (isset($url['lastmod']) && !empty($url['lastmod'])) {
                    $sitemap .= '    <lastmod>' . htmlspecialchars($url['lastmod'], ENT_XML1, 'UTF-8') . '</lastmod>' . "\n";
                }
                if (isset($url['priority']) && !empty($url['priority'])) {
                    $sitemap .= '    <priority>' . htmlspecialchars($url['priority'], ENT_XML1, 'UTF-8') . '</priority>' . "\n";
                }
                if (isset($url['changefreq']) && !empty($url['changefreq'])) {
                    $sitemap .= '    <changefreq>' . htmlspecialchars($url['changefreq'], ENT_XML1, 'UTF-8') . '</changefreq>' . "\n";
                }
                $sitemap .= '  </url>' . "\n";
            }

            $sitemap .= '</urlset>';

            return response($sitemap, 200)
                ->header('Content-Type', 'application/xml; charset=utf-8')
                ->header('Cache-Control', 'public, max-age=3600');
        } catch (\Exception $e) {
            // Return minimal valid XML on error
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            $sitemap .= '</urlset>';
            
            return response($sitemap, 200)
                ->header('Content-Type', 'application/xml; charset=utf-8');
        }
    }
}

