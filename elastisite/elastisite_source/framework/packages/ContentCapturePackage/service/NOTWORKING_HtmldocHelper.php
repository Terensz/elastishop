<?php 
namespace framework\packages\ContentCapturePackage\service;

use App;
use framework\component\parent\Service;
use hexydec\html\htmldoc;

class HtmldocHelper extends Service
{
    public static function test($url)
    {
        // App::getContainer()->wireService('ContentCapturePackage/service/CurlHelper');
        $currentPath = __DIR__;
        $parentPath = dirname($currentPath);
        // dump(App::$frameworkDirAbsolutePath);
        // dump($parentPath);
        include_once(App::$frameworkDirAbsolutePath.'/vendor/htmldoc/src/autoload.php');
        $doc = new htmldoc();
        $doc->open($url);
        // $cards = $doc->find('.blockWrapper')->first()->html();

        var_dump($doc->getAll());exit;

        // $rawContent = CurlHelper::get('https://tq-db.net/en/category/head');
        $rawContent = CurlHelper::get('http://meheszellato.hu/');

        // App::getContainer()->wireService('ContentCapturePackage/service/CurlHelper');
        // // $rawContent = CurlHelper::get('https://tq-db.net/en/category/head');
        // $rawContent = CurlHelper::get('http://meheszellato.hu/');
        dump(htmlspecialchars($rawContent));

        App::getContainer()->wireService('ContentCapturePackage/service/ContentProcessor');
        $processedContent = ContentProcessor::process($rawContent);
        var_dump($rawContent);
        dump($processedContent);

        dump('teszt ======');exit;
    }
}
