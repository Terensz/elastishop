<?php
namespace framework\kernel\request;

use framework\kernel\component\Kernel;

class Request extends Kernel
{
    private $requests;
    private $headers;
    private $submitted;

    public function setSubmitted($submitted)
    {
        $this->submitted = $submitted;
    }

    public function isSubmitted()
    {
        return ($this->submitted) ? true : false;
    }

    public function get($key)
    {
        return (isset($this->requests[$key])) ? $this->requests[$key] : null;
    }

    public function set($key, $value)
    {
        $this->requests[$key] = $value;
    }

    public function getAll()
    {
        return $this->requests;
    }

    public function setHeaders()
    {
        if (!$this->headers) {
            $this->headers = getallheaders();
        }
        return true;
    }

    public function getAllHeaders()
    {
        $this->setHeaders();
        return $this->headers;
    }

    public function getHeader($key)
    {
        $this->setHeaders();
        return isset($this->headers[$key]) ? $this->headers[$key] : null;
    }
}
