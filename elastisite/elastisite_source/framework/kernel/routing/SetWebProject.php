<?php
namespace framework\kernel\routing;

// use framework\kernel\component\Kernel;

use App;
use framework\component\helper\UrlHelper;
use framework\kernel\utility\FileHandler;

class SetWebProject
{
    const DEFAULT_SITE = 'DefaultSite';

    public function __construct()
    {
        # Ha van beallitva webProjectName, akkor az. Semmi mas.
        $dirs = FileHandler::getAllDirNames('project/sites');
        $webProjectName = App::getContainer()->getConfig()->getGlobal('server.webProjectName');
        if ($webProjectName) {
            App::setWebProject($webProjectName);
            return;
        }

        # Ha egy webProject van, akkor legyen az
        if (count($dirs) == 1) {
            App::setWebProject($dirs[0]);
        }

        # Ha 2 website van, akkor legyen a NEM DefaultSite
        if (count($dirs) == 2) {
            foreach ($dirs as $dir) {
                if ($dir != self::DEFAULT_SITE) {
                    App::setWebProject($dir);
                    return;
                }
            }
        }

        # Ha tobb, mint 2 website van, akkor legyen a DefaultSite, de azert menjunk tovabb, hatha van meg feltetel
        if (count($dirs) > 2) {
            App::setWebProject(self::DEFAULT_SITE);
        }

        # Ha van tovabbi felteteled, akkor ird ide!
    }
}
