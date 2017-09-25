<?php namespace Anomaly\LocalDocumentationExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\DocumentationModule\Documentation\DocumentationExtension;
use Anomaly\DocumentationModule\Documentation\DocumentationParser;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class GetPages
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GetPages
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
     * The loading path.
     *
     * @var array
     */
    protected $path;

    /**
     * Create a new GetPages instance.
     *
     * @param DocumentationExtension $extension
     * @param string                 $reference
     * @param array                  $path
     */
    public function __construct(DocumentationExtension $extension, $reference, $path = null)
    {
        $this->extension = $extension;
        $this->reference = $reference;
        $this->path      = $path;
    }

    /**
     * Handle the command.
     *
     * @param ConfigurationRepositoryInterface $configuration
     * @param DocumentationParser              $parser
     * @param Filesystem                       $files
     * @return array
     */
    public function handle(
        ConfigurationRepositoryInterface $configuration,
        DocumentationParser $parser,
        Filesystem $files
    ) {
        $pages = [];

        $path = $prefix = $configuration->value(
            $this->extension->getNamespace('path'),
            $this->extension->getProjectId()
        );

        if ($this->path) {
            $path .= DIRECTORY_SEPARATOR . $this->path;
        }

        $path   = base_path($path);
        $prefix = base_path($prefix);

        $directories = $files->directories($path);
        $files       = $files->files($path);

        array_map(
            function ($directory) use ($prefix, $path, &$pages) {

                $path = ltrim(str_replace($prefix, '', $directory), DIRECTORY_SEPARATOR);

                $pages[basename($directory)] = $this->dispatch(
                    new GetPages($this->extension, $this->reference, $path)
                );
            },
            $directories
        );

        array_map(
            function (\SplFileInfo $file) use ($parser, &$pages) {

                $content = file_get_contents($file->getPathname());

                $pages[$file->getBasename('.' . $file->getExtension())] = array_merge(
                    $parser->attributes($content),
                    ['content' => $parser->content($content)]
                );
            },
            $files
        );

        return $pages;
    }
}
