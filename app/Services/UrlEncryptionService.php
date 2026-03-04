<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class UrlEncryptionService
{
    public function encryptUrl(string $url): string
    {
        $encrypted = Crypt::encryptString($url);
        return base64_encode($encrypted);
    }

    public function decryptUrl(string $encrypted): ?string
    {
        try {
            $decoded = base64_decode($encrypted);
            return Crypt::decryptString($decoded);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function encryptPath(string $path, array $params = []): string
    {
        $fullUrl = $path;
        if (!empty($params)) {
            $fullUrl .= '?' . http_build_query($params);
        }
        
        return $this->encryptUrl($fullUrl);
    }

    public function shouldEncrypt(string $path): bool
    {
        $excludedPaths = [
            'login',
            'register',
            'password',
            'webhooks',
            'debug',
            '_debugbar',
        ];

        foreach ($excludedPaths as $excluded) {
            if (str_contains($path, $excluded)) {
                return false;
            }
        }

        return true;
    }
}
