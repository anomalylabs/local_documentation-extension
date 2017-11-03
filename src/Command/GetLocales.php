<?php namespace Anomaly\LocalDocumentationExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\DocumentationModule\Documentation\DocumentationExtension;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class GetLocales
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GetLocales
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
     * Create a new GetLocales instance.
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
     * @param Filesystem                       $files
     * @return array
     */
    public function handle(ConfigurationRepositoryInterface $configuration, Filesystem $files)
    {

        $path = $configuration->value(
            $this->extension->getNamespace('path'),
            $this->extension->getProjectId()
        );

        if (!is_dir(base_path($path))) {
            return [];
        }

        $path = base_path($path);

        $directories = $files->directories($path);

        return array_map(
            function ($directory) use ($path) {
                return ltrim(str_replace($path, '', $directory), DIRECTORY_SEPARATOR);
            },
            $directories
        );
    }
}
