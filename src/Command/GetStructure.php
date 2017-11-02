<?php namespace Anomaly\LocalDocumentationExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\DocumentationModule\Documentation\DocumentationExtension;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;

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
     * The locale to scan.
     *
     * @var array
     */
    protected $locale;

    /**
     * The loading path.
     *
     * @var array
     */
    protected $path;

    /**
     * Create a new GetStructure instance.
     *
     * @param DocumentationExtension $extension
     * @param string                 $reference
     * @param                        $locale
     * @param array                  $path
     */
    public function __construct(DocumentationExtension $extension, $reference, $locale, $path = null)
    {
        $this->extension = $extension;
        $this->reference = $reference;
        $this->locale    = $locale;
        $this->path      = $path;
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

        $pages = [];

        $path = $prefix = $configuration->value(
                $this->extension->getNamespace('path'),
                $this->extension->getProjectId()
            ) . DIRECTORY_SEPARATOR . $this->locale;

        if (!is_dir(base_path($prefix))) {
            return [];
        }

        if ($this->path) {
            $path .= DIRECTORY_SEPARATOR . $this->path;
        }

        $path   = base_path($path);
        $prefix = base_path($prefix);

        $directories = $files->directories($path);
        $files       = $files->files($path);

        array_map(
            function (\SplFileInfo $file) use ($prefix, &$pages) {
                $pages[] = str_replace($prefix, '', $file->getPath())
                    . DIRECTORY_SEPARATOR
                    . basename($file->getFilename(), '.' . $file->getExtension());
            },
            $files
        );

        array_map(
            function ($directory) use ($prefix, $path, &$pages) {

                $path = ltrim(str_replace($prefix, '', $directory), DIRECTORY_SEPARATOR);

                $pages = array_merge(
                    $pages,
                    $this->dispatch(
                        new GetStructure($this->extension, $this->reference, $this->locale, $path)
                    )
                );
            },
            $directories
        );

        return $pages;
    }
}
