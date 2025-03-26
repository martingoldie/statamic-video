<?php

namespace Upscope\Vimeo;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Tags\Vimeo::class,
    ];
}
