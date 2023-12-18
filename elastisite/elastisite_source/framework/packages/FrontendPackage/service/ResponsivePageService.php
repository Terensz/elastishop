<?php
namespace framework\packages\FrontendPackage\service;

use framework\component\parent\Service;

class ResponsivePageService extends Service
{
    const MIN_FONT_SIZE = 6;

    const PHONE_SCREEN_MAX_WIDTH = 475;

    const SMALL_TABLET_SCREEN_MAX_WIDTH = 768;

    const TABLET_SCREEN_MAX_WIDTH = 1199;

    const CONTENT_EDITOR_WORKS_AT_MIN_WIDTH = self::TABLET_SCREEN_MAX_WIDTH + 1;

    // const COMPUTER_SCREEN_MAX_WIDTH = '';
}