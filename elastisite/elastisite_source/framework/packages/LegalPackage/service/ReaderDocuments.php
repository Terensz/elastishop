<?php
namespace framework\packages\LegalPackage\service;

use framework\kernel\utility\BasicUtils;
use framework\component\parent\Service;
use framework\packages\LegalPackage\entity\LegalDocument;
use framework\kernel\base\Reflector;
use framework\component\exception\ElastiException;
use framework\kernel\utility\FileHandler;
use framework\component\parent\Rendering;

class ReaderDocuments extends Rendering
{
    private $slug;
    private $fallbackSlug = 'documentNotFound.php';
    private $viewPathBase;
    private $viewsOfSlugs;

    public function setViewsOfSlugs($viewsOfSlugs)
    {
        $this->viewsOfSlugs = $viewsOfSlugs;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function setFallbackSlug($fallbackSlug)
    {
        $this->fallbackSlug = array_values($fallbackSlug)[0];
    }

    public function setViewPathBase($viewPathBase)
    {
        $this->viewPathBase = $viewPathBase;
    }

    public function render()
    {
        // dump($this->getContainer()->getLocale());
        if (isset($this->viewsOfSlugs[$this->slug])) {
            $localeAndFile = $this->getContainer()->getLocale().'/'.$this->viewsOfSlugs[$this->slug];
        } else {
            $localeAndFile = $this->getContainer()->getLocale().'/'.$this->fallbackSlug;
        }
        $viewPath = $this->viewPathBase.$localeAndFile;


        $fileExists = FileHandler::fileExists($viewPath, 'source');
        if ($fileExists) {
            $view = $this->renderView(FileHandler::completePath($viewPath), [
                    'container' => $this->getContainer(), 
                    'slug' => $this->slug
                ]
            );
            return $view;
            // dump($view);exit;
        } else {
            dump($viewPath);exit;
        }
    }
}
