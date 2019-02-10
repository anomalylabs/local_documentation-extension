<?php namespace Anomaly\LocalDocumentationExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\DocumentationModule\Documentation\DocumentationExtension;
use Anomaly\DocumentationModule\Documentation\DocumentationParser;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class GetPage
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GetPage
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
     * Create a new GetPage instance.
     *
     * @param DocumentationExtension $extension
     * @param string $reference
     * @param                        $locale
     * @param                        $path
     */
    public function __construct(DocumentationExtension $extension, $reference, $locale, $path)
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
     * @param DocumentationParser $parser
     * @param Filesystem $files
     * @return array
     */
    public function handle(
        ConfigurationRepositoryInterface $configuration,
        DocumentationParser $parser,
        Filesystem $files
    ) {
        $path = $configuration->value(
                $this->extension->getNamespace('path'),
                $this->extension->getProjectId()
            ) . DIRECTORY_SEPARATOR . $this->locale;

        $segments = explode(DIRECTORY_SEPARATOR, ltrim($this->path, DIRECTORY_SEPARATOR));

        $last = array_pop($segments);

        $parts = explode('.', $last);

        $path = base_path($path);

        $file = $files->get($path . DIRECTORY_SEPARATOR . $this->path . '.md');

        $data    = $parser->attributes($file);
        $content = $parser->content($file);

        return [
            'title'            => array_pull($data, 'title'),
            'meta_title'       => array_pull($data, 'meta_title'),
            'meta_description' => array_pull($data, 'meta_description'),
            'path'             => $parser->path($this->path, DIRECTORY_SEPARATOR),
            'sort_order'       => (int)array_shift($parts),
            'content'          => $content,
            'data'             => $data,
        ];
    }
}
