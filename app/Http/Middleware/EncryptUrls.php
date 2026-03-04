<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\UrlEncryptionService;

class EncryptUrls
{
    protected $encryptionService;

    public function __construct(UrlEncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('_encrypted')) {
            $encryptedUrl = $request->get('_encrypted');
            $decrypted = $this->encryptionService->decryptUrl($encryptedUrl);
            
            if ($decrypted) {
                $parts = parse_url($decrypted);
                $path = $parts['path'] ?? '/';
                
                parse_str($parts['query'] ?? '', $query);
                $request->merge($query);
                
                $request->server->set('REQUEST_URI', $path);
            }
        }

        $response = $next($request);

        if ($response->headers->get('Content-Type') && 
            str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $content = $response->getContent();
            
            $content = preg_replace_callback(
                '/href=["\']([^"\']+)["\']/',
                function ($matches) use ($request) {
                    $url = $matches[1];
                    
                    if (str_starts_with($url, 'http') || 
                        str_starts_with($url, '#') || 
                        str_starts_with($url, 'javascript:')) {
                        return $matches[0];
                    }
                    
                    $path = parse_url($url, PHP_URL_PATH) ?? $url;
                    
                    if ($this->encryptionService->shouldEncrypt($path)) {
                        $encrypted = $this->encryptionService->encryptUrl($url);
                        return 'href="?_encrypted=' . urlencode($encrypted) . '"';
                    }
                    
                    return $matches[0];
                },
                $content
            );
            
            $response->setContent($content);
        }

        return $response;
    }
}
