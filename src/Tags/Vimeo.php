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

		if ($url) {
			 $id = Uri::of($url)->path();
		} else {
			return;
		}

        $video = VimeoService::getVideo($id);
        $video_files = $video['files'];
        foreach ($video_files as $video_file) {
            if ($video_file['quality'] === 'hls' && $video_file['rendition'] === 'adaptive') {
                $hls = $video_file;
            } else {
                $files[] = $video_file;
            }
        }

        $hls = $hls ?? null;
        $files = $files ? array_reverse($files) : [];
        $key_values = array_column($files, 'width');
        array_multisort( $key_values, SORT_DESC, $files);

//        Play Progressive links have expiry dates so they work well on a dynamic website but not on a static site.
//        $hls = $video['play']['hls'] ?? null;
//        $progressive = $video['play']['progressive'] ?? [];
//        return collect($progressive)

        return collect($files)
            ->prepend($hls ? [...$hls, 'type' => 'application/x-mpegURL'] : null)
            ->filter()
            ->all();
    }
}
