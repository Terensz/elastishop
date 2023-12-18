<?php 
namespace framework\packages\ContentCapturePackage\service;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use hexydec\html\htmldoc;
use simple_html_dom;

class ContentCaptureHelper extends Service
{
    public $factoryCode;
    public $httpDomain;
    public $productListRelativeUrl;
    public $productWrapperClass;
    public $productNameContainerClass;
    public $productThumbnailsContainerClass;
    public $productDetailsLinkBearerClass;
    public $productDetailsLinkBearerAttribute = 'scr';
    public $productDetailsLinkContainsArticleNumber = false;

    public function parse()
    {
        $productData = [];
        // App::getContainer()->wireService('ContentCapturePackage/service/CurlHelper');
        // $currentPath = __DIR__;
        // $parentPath = dirname($currentPath);
        // dump(App::$frameworkDirAbsolutePath);
        // dump($parentPath);
        include_once(App::$frameworkDirAbsolutePath.'/vendor/SimpleHtmlDom/simple_html_dom.php');

        $documentDom = new simple_html_dom();
        $fullProductListUrl = trim($this->httpDomain, '/').'/'.trim($this->productListRelativeUrl, '/');
        $documentDom->load_file($fullProductListUrl);

        // dump($fullProductListUrl);

        // $html->load('
        // <div>
        //     <div class="product-item__wrapper">
        //         Alma 1
        //     </div>
        //     <div class="product-item__wrapper">
        //         Alma 2
        //     </div>
        //     <div class="product-item__wrapper">
        //         Körte
        //     </div>
        // </div>');

        // dump($html->nodes);exit;

        // $elements = $html->find('.product-name');
        $productIndex = 0;
        foreach($documentDom->find('.'.$this->productWrapperClass) as $productWrapper) {
            $productWrapperDom = new simple_html_dom(); 
            $productWrapperDom->load($productWrapper->innertext);

            $productData[$productIndex]['productName'] = null;
            foreach ($productWrapperDom->find('.'.$this->productNameContainerClass) as $productNameElement) {
                // dump($productNameElement->innertext);
                $productData[$productIndex]['productName'] = $productNameElement->innertext;
            }

            $productData[$productIndex]['productDetailsUrl'] = null;
            foreach ($productWrapperDom->find('.'.$this->productDetailsLinkBearerClass) as $productDetailsLinkBearerElement) {
                // dump($productDetailsLinkBearerElement->getAttribute($this->productDetailsLinkBearerAttribute));
                $productData[$productIndex]['productDetailsUrl'] = $productDetailsLinkBearerElement->getAttribute($this->productDetailsLinkBearerAttribute);
            }

            if ($this->productDetailsLinkContainsArticleNumber) {
                BasicUtils::explodeAndGetElement($productData[$productIndex]['productDetailsUrl'], '/', 'last');
            }

            $productData[$productIndex]['productDetailsUrl'] = $productData[$productIndex]['productDetailsUrl'] ? $this->httpDomain.$productData[$productIndex]['productDetailsUrl'] : null;

            $productIndex++;
        }
            // $productName = $productWrapper->children('.'.$this->productNameContainerClass);
            // $productName = $productWrapper->children();
            // dump($productName);
            // dump($element->innertext);
            // dump(htmlentities($productWrapper->innertext));

        // foreach($html->find('.pagination__link') as $element) {
        //     // echo $element;
        //     dump($element);
        // }

        dump($productData);
        dump('== end ==');exit;

        // require_once 'simplehtmldom/simple_html_dom.php';

        $input = '<p>Hello &#8211; World</p>';
        
        $dom = str_get_html($input, false, false, 'UTF-8', false);
        $output = $dom->save();
        
        echo $input; //  Prints: <p>Hello &#8211; World</p>
        echo $output; // Prints: <p>Hello &ndash; World</p>

    }
}

/*
Ahhoz, hogy hatákonyan tudjuk a termékeket összeszinkronizálni a mi rendszerünkkel elsősorban arra lenne szükségünk, hogy olyan hívást tudjunk az Önök szervere felé kezdeményezni, ami JSON, XML, vagy valamilyen könnyen tömbbé alakítható formában adja vissza a termékek adatait, mert ellenkező esetben a teljes html-kódból kellene kinyernünk az adatokat, ami egy nagyságrenddel nagyobb fejlesztési munka.

*/

