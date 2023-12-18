<?php
namespace framework\packages\ArticlePackage\loader;

use framework\component\parent\PackageLoader;

class Loader extends PackageLoader
{
    public function __construct()
    {
        // $this->wireService('ArticlePackage/entity/Article');
        // $this->setService('ArticlePackage/repository/ArticleRepository');
        // $repo = $this->getService('ArticleRepository');
        // $repo->setEmulateAutoIncrement('id');
        // $repo->setProperties(['id', 'title', 'teaser', 'teaserImageLink', 'slug', 'body']);
        // $repo->setUniqueProperties(['title', 'slug']);
    }
}
