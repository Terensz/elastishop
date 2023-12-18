<?php
namespace framework\kernel\request;

use framework\kernel\component\Kernel;
use framework\kernel\request\Upload;

class SetUploadRequests extends Kernel
{
    public function __construct()
    {
        $this->collectAndSetUploads();
    }

    public function collectAndSetUploads()
    {
        foreach ($_FILES as $key => $value) {
            // dump($_FILES);exit;
            $upload = new Upload();
            $upload->setError(isset($value['error']) ? $value['error'] : null);
            $upload->setName(isset($value['name']) ? $value['name'] : null);
            $upload->setSize(isset($value['size']) ? $value['size'] : null);
            $upload->setTmpName(isset($value['tmp_name']) ? $value['tmp_name'] : null);
            $upload->setMime(isset($value['type']) ? $value['type'] : null);
            // $upload->setMime(mime_content_type($upload->getTmpName()));
            // $upload->setMime(isset($value['type']) ? $value['type'] : null);
            if (in_array($upload->getMime(), array('image/jpeg', 'image/gif', 'image/png'))) {
                $imageSize = getimagesize($value['tmp_name']);
                $upload->setWidth($imageSize[0]);
                $upload->setHeight($imageSize[1]);
            }
            $this->getContainer()->getKernelObject('UploadRequest')->set(((int)$key == 0 ? 0 : $key), $upload);
        }
    }
}
