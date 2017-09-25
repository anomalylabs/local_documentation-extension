<?php namespace Anomaly\LocalDocumentationExtension;

use Anomaly\ConfigurationModule\Configuration\Form\ConfigurationFormBuilder;
use Anomaly\DocumentationModule\Documentation\DocumentationExtension;
use Anomaly\LocalDocumentationExtension\Command\GetComposer;
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
     * Return the documentation structure.
     *
     * @param $reference
     * @return array
     */
    public function structure($reference)
    {
        return $this->dispatch(new GetStructure($this, $reference));
    }

    /**
     * Return the documentation pages.
     *
     * @param $reference
     * @return array
     */
    public function pages($reference)
    {
        return $this->dispatch(new GetPages($this, $reference));
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
