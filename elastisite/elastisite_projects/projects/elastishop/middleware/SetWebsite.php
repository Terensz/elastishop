<?php
namespace projects\elastishop\middleware;

// use framework\kernel\component\Kernel;

use App;
use framework\kernel\utility\FileHandler;

class SetWebsite
{
    const POSSIBLE_WEBSITE_DOMAINS = ['elastishop'];

    public function __construct()
    {
        $fullDomain = App::getContainer()->getUrl()->getFullDomain();
        // dump($fullDomain);
        $website = App::getWebProject();
        if (in_array($fullDomain, self::POSSIBLE_WEBSITE_DOMAINS)) {
            $website = 'elastishop';
        }
        App::setWebsite($website);
    }
}
