<?php
namespace framework\kernel\request;

use framework\kernel\component\Kernel;

class UploadRequest extends Kernel
{
    private $uploads;

    public function get($key = null)
    {
        if ($key == null) {
            foreach ($this->uploads as $upload) {
                return $upload;
            }
        }
        return (isset($this->uploads[$key])) ? $this->uploads[$key] : null;
    }

    public function set($key, $upload)
    {
        $this->uploads[$key] = $upload;
    }

    public function getAll()
    {
        return $this->uploads;
    }
}
