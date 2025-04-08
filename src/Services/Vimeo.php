<?php

namespace Upscope\Vimeo\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Vimeo
{
    public static function getVideo($id)
    {
        return Cache::remember('vimeo-video-'.$id, 60 * 60 * 24, function () use ($id) {
            $response = self::makeRequest()
                ->get('https://api.vimeo.com/videos/'.$id, [
                    'fields' => ['play', 'files'],
                ]);

            return $response->json();
        });
    }

    protected static function makeRequest(): PendingRequest
    {
        return Http::createPendingRequest()
            ->throw()
            ->withToken(config('services.vimeo.access_token'))
            ->withHeaders([
                'Accept' => 'application/vnd.vimeo.*+json;version=3.4',
            ]);
    }
}
