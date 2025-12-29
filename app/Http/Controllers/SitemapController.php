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

        // Add SEO landing pages for all ad types
        $actions = ['خرید', 'فروش'];
        $types = [
            'گروه-تلگرام',
            'کانال-تلگرام',
            'پیج-اینستاگرام',
            'سایت-آماده',
            'دامنه',
            'کانال-یوتیوب',
        ];
        
        $seoPages = [];
        foreach ($actions as $action) {
            foreach ($types as $type) {
                $seoPages[] = ['action' => $action, 'type' => $type];
            }
        }

        foreach ($seoPages as $page) {
            try {
                $url = route('seo.landing', $page);
                // Validate URL is accessible (not 404)
                // Only add if route generation succeeds and parameters are valid
                if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
                    $urls[] = [
                        'loc' => $url,
                        'priority' => '0.85',
                        'changefreq' => 'daily',
                        'lastmod' => now()->toAtomString(),
                    ];
                }
            } catch (\Exception $e) {
                // Skip if route generation fails
                continue;
            }
        }

        return $this->generateSitemap($urls);
    }

    public function ads()
    {
        try {
            // SEO Rules: Only indexable ads should be in sitemap
            // Criteria: active, not expired, not deleted, sufficient content (min 50 chars)
            $ads = Ad::where('status', 'active')
                ->where('is_active', true)
                ->where(function($q) {
                    // آگهی‌هایی که expire_at ندارند یا expire_at آنها در آینده است
                    $q->whereNull('expire_at')
                      ->orWhere('expire_at', '>', now());
                })
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->whereRaw('CHAR_LENGTH(TRIM(description)) >= 50') // Minimum 50 characters description
                ->latest()
                ->get();

            $urls = $ads->map(function ($ad) {
                try {
                    // Double-check ad is still indexable before adding to sitemap
                    if ($ad->trashed() || 
                        $ad->status !== 'active' || 
                        !$ad->is_active ||
                        ($ad->expire_at && $ad->expire_at <= now()) ||
                        mb_strlen(trim($ad->description ?? '')) < 50) {
                        return null;
                    }
                    
                    $url = route('store.show', $ad->slug);
                    if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
                        return null;
                    }
                    
                    return [
                        'loc' => $url,
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
                    // Validate post is still published and accessible
                    if (!$post->isPublished() || empty($post->slug)) {
                        return null;
                    }
                    
                    $url = route('blog.post', $post->slug);
                    if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
                        return null;
                    }
                    
                    return [
                        'loc' => $url,
                        'lastmod' => $post->updated_at ? $post->updated_at->toAtomString() : now()->toAtomString(),
                        'priority' => '0.6',
                        'changefreq' => 'monthly',
                    ];
                } catch (\Exception $e) {
                    // Skip posts that can't generate route
                    return null;
                }
            })->filter()->toArray();
            
            // Always include the blog index page to ensure at least one URL
            try {
                $blogIndexUrl = route('blog.index');
                if ($blogIndexUrl && filter_var($blogIndexUrl, FILTER_VALIDATE_URL)) {
                    // Check if blog index is already in the URLs array
                    $blogIndexExists = false;
                    foreach ($urls as $url) {
                        if (isset($url['loc']) && rtrim(strtolower($url['loc']), '/') === rtrim(strtolower($blogIndexUrl), '/')) {
                            $blogIndexExists = true;
                            break;
                        }
                    }
                    
                    if (!$blogIndexExists) {
                        array_unshift($urls, [
                            'loc' => $blogIndexUrl,
                            'lastmod' => now()->toAtomString(),
                            'priority' => '0.8',
                            'changefreq' => 'daily',
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // If blog index route fails, we'll handle it in the catch block below
            }

            // Ensure we have at least one URL
            if (empty($urls)) {
                $urls = [
                    [
                        'loc' => url('/blog'),
                        'lastmod' => now()->toAtomString(),
                        'priority' => '0.8',
                        'changefreq' => 'daily',
                    ]
                ];
            }

            return $this->generateSitemap($urls);
        } catch (\Exception $e) {
            // Return sitemap with at least blog index page on error
            try {
                $blogIndexUrl = route('blog.index');
                if ($blogIndexUrl && filter_var($blogIndexUrl, FILTER_VALIDATE_URL)) {
                    return $this->generateSitemap([
                        [
                            'loc' => $blogIndexUrl,
                            'lastmod' => now()->toAtomString(),
                            'priority' => '0.8',
                            'changefreq' => 'daily',
                        ]
                    ]);
                }
            } catch (\Exception $e2) {
                // Fallback to direct URL if route generation fails
            }
            
            // Final fallback - use direct URL
            return $this->generateSitemap([
                [
                    'loc' => url('/blog'),
                    'lastmod' => now()->toAtomString(),
                    'priority' => '0.8',
                    'changefreq' => 'daily',
                ]
            ]);
        }
    }

    private function generateSitemap(array $urls)
    {
        try {
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

            // Remove duplicates based on 'loc' to prevent duplicate content issues
            $uniqueUrls = [];
            $seenUrls = [];
            
            foreach ($urls as $url) {
                if (!isset($url['loc']) || empty($url['loc'])) {
                    continue; // Skip invalid URLs
                }
                
                // Normalize URL (remove trailing slash, convert to lowercase for comparison)
                $normalizedUrl = rtrim(strtolower($url['loc']), '/');
                
                // Skip if we've seen this URL before (prevent duplicates)
                if (isset($seenUrls[$normalizedUrl])) {
                    continue;
                }
                
                $seenUrls[$normalizedUrl] = true;
                $uniqueUrls[] = $url;
            }
            
            // If no valid URLs, return minimal valid sitemap with at least one URL
            // Sitemap protocol requires at least one <url> entry
            if (empty($uniqueUrls)) {
                $baseUrl = url('/');
                $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
                $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
                $sitemap .= '  <url>' . "\n";
                $sitemap .= '    <loc>' . htmlspecialchars($baseUrl, ENT_XML1, 'UTF-8') . '</loc>' . "\n";
                $sitemap .= '    <lastmod>' . htmlspecialchars(now()->toAtomString(), ENT_XML1, 'UTF-8') . '</lastmod>' . "\n";
                $sitemap .= '    <priority>1.0</priority>' . "\n";
                $sitemap .= '    <changefreq>daily</changefreq>' . "\n";
                $sitemap .= '  </url>' . "\n";
                $sitemap .= '</urlset>';
                
                return response($sitemap, 200)
                    ->header('Content-Type', 'application/xml; charset=utf-8');
            }
            
            foreach ($uniqueUrls as $url) {
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

