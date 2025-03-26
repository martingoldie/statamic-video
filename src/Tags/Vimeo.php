<?php

namespace Upscope\Vimeo\Tags;

use Illuminate\Support\Uri;
use Upscope\Vimeo\Services\Vimeo as VimeoService;
use Statamic\Tags\Tags;

class Vimeo extends Tags
{
    public function sources()
    {
        $url = $this->params->get('url');

        $id = Uri::of($url)->path();

        $video = VimeoService::getVideo($id);

        $hls = $video['play']['hls'] ?? null;
        $progressive = $video['play']['progressive'] ?? [];

        return collect($progressive)
            ->prepend($hls ? [...$hls, 'type' => 'application/x-mpegURL'] : null)
            ->filter()
            ->all();
    }
}
