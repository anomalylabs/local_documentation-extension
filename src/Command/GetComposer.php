<?php namespace Anomaly\LocalDocumentationExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\DocumentationModule\Documentation\DocumentationExtension;
use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class GetComposer
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GetComposer
{

    use DispatchesJobs;

    /**
     * The documentation extension.
     *
     * @var DocumentationExtension
     */
    protected $extension;

    /**
     * The project reference.
     *
     * @var string
     */
    protected $reference;

    /**
     * Create a new GetComposer instance.
     *
     * @param DocumentationExtension $extension
     * @param string                 $reference
     */
    public function __construct(DocumentationExtension $extension, $reference)
    {
        $this->extension = $extension;
        $this->reference = $reference;
    }

    /**
     * Handle the command.
     *
     * @param ConfigurationRepositoryInterface $configuration
     * @param AddonCollection                  $addons
     * @return \stdClass
     */
    public function handle(ConfigurationRepositoryInterface $configuration, AddonCollection $addons)
    {
        $project = $this->extension->getProject();

        $namespace = 'anomaly.extension.local_documentation';

        /* @var Addon $addon */
        if ($addon = $addons->get($key = $configuration->value($namespace . '::addon', $project->getSlug()))) {
            $path = $addon->getPath('composer.json');
        }

        if (!isset($path)) {
            $path = base_path($key . '/composer.json');
        }

        return json_decode(file_get_contents($path));
    }
}
