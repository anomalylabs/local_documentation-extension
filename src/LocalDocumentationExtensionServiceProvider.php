<?php

namespace Anomaly\LocalDocumentationExtension;

use Illuminate\Contracts\Support\DeferrableProvider;
use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

/**
 * Class LocalDocumentationExtensionServiceProvider
 *
 * @link   http://pyrocms.com/
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class LocalDocumentationExtensionServiceProvider extends AddonServiceProvider implements DeferrableProvider
{

    /**
     * Return the provided services.
     */
    public function provides()
    {
        return [LocalDocumentationExtension::class, 'anomaly.extension.local_documentation'];
    }
}
