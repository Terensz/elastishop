<?php
namespace framework\component\parent;

use framework\component\parent\Response;

class ImageResponse extends Response
{
    public function __construct($path)
    {
        $this->setService('ToolPackage/service/ImageService');
        $imageService = $this->getService('ImageService');
        $ext = $imageService->determineExtension($path);
        // $mime = $ext == 'jpg' ? 'jpeg' : $ext;
        if ($ext) {
            if ($ext == 'svg') {
                return $this->displaySvg($path);
            }
            header('Content-type: image/'.$ext);
            header('Cache-Control: public');
            // $resource = fopen($path, 'rb');
            // fpassthru($resource);
            readfile($path);
            // fclose($resource);
            // switch ($mime) {
            //     case 'jpeg':
            //         imagejpeg(imagecreatefromjpeg($path), null);
            //     case 'gif':
            //         imagegif(imagecreatefromgif($path), null);
            //     case 'png':
            //         $size = getimagesize($path);
            //         $width = $size[0];
            //         $height = $size[1];
            //         $newImage = imagecreatetruecolor($width, $height);
            //         imagealphablending($newImage, false);
            //         imagesavealpha($newImage, true);
            //         $source = imagecreatefrompng($path);
            //         imagealphablending($source, true);
            //         imagecopyresampled($newImage, $source, 0, 0, 0, 0, $width, $height, $width, $height);
            //         imagepng($newImage, null);
            //         break;
            //     default:
            //         break;
            // }
        }
    }

    public function displaySvg($path)
    {
        // $svg_file = file_get_contents($path);
        // $find_string   = '<svg';
        // $position = strpos($svg_file, $find_string);
        // $svg_file_new = substr($svg_file, $position);
        // echo "<div style='width:100%; height:100%;' >" . $svg_file_new . "</div>";
        // dump($path);exit;

        // $doc = new \DOMDocument();
        // $doc->loadXML($path);
        // $svg = $doc->getElementsByTagName('svg');
        // echo '<div style="width: 100%; height: 100%;">';
        // echo $svg->item(0)->C14N();
        // echo '</div>';

        // $iconfile = new \DOMDocument();
        // $iconfile->load($path);
        // echo $iconfile->saveHTML($iconfile->getElementsByTagName('svg')[0]);

        $svg = file_get_contents($path);
        header('Content-type: image/svg+xml');
        // $svg = str_replace("{{class-name}}", $array[$index], $svg);
        echo $svg;
    }
}
