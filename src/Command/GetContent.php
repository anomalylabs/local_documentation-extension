<?php namespace Anomaly\LocalDocumentationExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\DocumentationModule\Project\Contract\ProjectInterface;
use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Local\Client;

/**
 * Class GetContent
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\LocalDocumentationExtension\Command
 */
class GetContent implements SelfHandling
{

    use DispatchesJobs;

    /**
     * The project instance.
     *
     * @var ProjectInterface
     */
    protected $project;

    /**
     * The project reference.
     *
     * @var string
     */
    protected $reference;

    /**
     * The documentation page.
     *
     * @var string
     */
    protected $page;

    /**
     * Create a new GetContent instance.
     *
     * @param ProjectInterface $project
     * @param string           $reference
     * @param string           $page
     */
    public function __construct(ProjectInterface $project, $reference, $page)
    {
        $this->project   = $project;
        $this->reference = $reference;
        $this->page      = $page;
    }

    /**
     * Handle the command.
     *
     * @param Repository                       $config
     * @param ConfigurationRepositoryInterface $configuration
     * @return string
     */
    public function handle(ConfigurationRepositoryInterface $configuration, AddonCollection $addons, Repository $config)
    {
        $namespace = 'anomaly.extension.local_documentation';

        /* @var Addon $addon */
        $addon = $addons->get($configuration->value($namespace . '::addon', $this->project->getSlug()));

        $path = 'docs/' . $config->get('app.locale') . '/' . $this->page . '.md';

        if (!file_exists($addon->getPath($path))) {
            $path = 'docs/' . $config->get('app.fallback_locale') . '/' . $this->page . '.md';
        }

        return file_get_contents($addon->getPath($path));
    }
}
