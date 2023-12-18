<?php 
namespace framework\packages\ContentCapturePackage\service;

use framework\component\parent\Service;

class ContentProcessor extends Service
{
    public static function process(string $html = null)
    {
        if (!$html) {
            return null;
        }

        $result = [];
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        $xpath = new \DOMXPath($dom);

        $headings = $xpath->query('//*[@class="t"]');
        foreach ($headings as $heading) {
            $result[] = $heading->textContent . "\n";
        }

        return $result;
    }
}