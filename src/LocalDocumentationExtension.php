<?php namespace Anomaly\LocalDocumentationExtension;

use Anomaly\DocumentationModule\Documentation\DocumentationExtension;
use Anomaly\DocumentationModule\Project\Contract\ProjectInterface;
use Anomaly\LocalDocumentationExtension\Command\GetComposer;
use Anomaly\LocalDocumentationExtension\Command\GetContent;
use Anomaly\LocalDocumentationExtension\Command\GetStructure;

/**
 * Class LocalDocumentationExtension
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\LocalDocumentationExtension
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
     * Return the documentation structure object.
     *
     * @param ProjectInterface $project
     * @param                  $reference
     * @return array
     */
    public function structure(ProjectInterface $project, $reference)
    {
        return $this->dispatch(new GetStructure($project, $reference));
    }

    /**
     * Return the composer json object.
     *
     * @param ProjectInterface $project
     * @param                  $reference
     * @return \stdClass
     */
    public function composer(ProjectInterface $project, $reference)
    {
        return $this->dispatch(new GetComposer($project, $reference));
    }

    /**
     * Return the page content for a project.
     *
     * @param ProjectInterface $project
     * @param                  $reference
     * @param                  $page
     * @return string
     */
    public function content(ProjectInterface $project, $reference, $page)
    {
        return $this->dispatch(new GetContent($project, $reference, $page));
    }
}
