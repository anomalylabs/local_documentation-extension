<?php namespace Anomaly\LocalDocumentationExtension;

use Anomaly\ConfigurationModule\Configuration\Form\ConfigurationFormBuilder;
use Anomaly\DocumentationModule\Documentation\DocumentationExtension;
use Anomaly\LocalDocumentationExtension\Command\GetComposer;
use Anomaly\LocalDocumentationExtension\Command\GetLocales;
use Anomaly\LocalDocumentationExtension\Command\GetPage;
use Anomaly\LocalDocumentationExtension\Command\GetPages;
use Anomaly\LocalDocumentationExtension\Command\GetStructure;

/**
 * Class LocalDocumentationExtension
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class LocalDocumentationExtension extends DocumentationExtension
{

    /**
     * This extension a documentation documentation
     * for projects hosted on Local.
     *
     * @var null|string
     */
    protected $provides = 'anomaly.module.documentation::documentation.local';

    /**
     * Return the available locales.
     *
     * @param $reference
     * @return array
     */
    public function locales($reference)
    {
        return $this->dispatch(new GetLocales($this, $reference));
    }

    /**
     * Return the documentation structure.
     *
     * @param $reference
     * @param $locale
     * @return array
     */
    public function structure($reference, $locale)
    {
        return $this->dispatch(new GetStructure($this, $reference, $locale));
    }

    /**
     * Return a documentation page.
     *
     * @param $reference
     * @return array
     */
    public function page($reference, $locale, $path)
    {
        return $this->dispatch(new GetPage($this, $reference, $locale, $path));
    }

    /**
     * Return the composer json object.
     *
     * @param $reference
     * @return \stdClass
     */
    public function composer($reference)
    {
        return $this->dispatch(new GetComposer($this, $reference));
    }

    /**
     * Validate the configuration.
     *
     * @param ConfigurationFormBuilder $builder
     * @return bool
     */
    public function validate(ConfigurationFormBuilder $builder)
    {
        throw new \Exception('Implement VALIDATE method');
    }
}
