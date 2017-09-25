<?php namespace Anomaly\LocalDocumentationExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\DocumentationModule\Documentation\DocumentationExtension;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Yaml\Yaml;

/**
 * Class GetStructure
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GetStructure
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
     * Create a new GetStructure instance.
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
     * @return array
     */
    public function handle(ConfigurationRepositoryInterface $configuration)
    {
        $path = $configuration->value(
                $this->extension->getNamespace('path'),
                $this->extension->getProjectId()
            ) . DIRECTORY_SEPARATOR;

        return (new Yaml())->parse(file_get_contents(base_path($path . 'structure.yaml')));
    }
}
