<?php
namespace framework\packages\SchedulePackage\loader;

use framework\component\parent\PackageLoader;

class Loader extends PackageLoader
{
    public function __construct()
    {
        $this->wireService('SchedulePackage/entity/Event');

        $this->setService('SchedulePackage/repository/EventRepository');
        $repo = $this->getService('EventRepository');
        $repo->setEmulateAutoIncrement('id');
        $repo->setProperties(['id', 'title', 'teaser', 'teaserImageLink', 'slug', 'body']);
        $repo->setUniqueProperties(['title', 'slug']);
    }
}
