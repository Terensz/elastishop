<?php
namespace framework\kernel\request;

use framework\kernel\utility\BasicUtils;
use framework\kernel\component\Kernel;

class SetPostRequests extends Kernel
{
    public function __construct()
    {
        $this->collectAndSetPostRequests();
    }

    // public function setCsrfToken($widgetId)
    // {
    //     if (!$this->getRequest()->get($widgetId.'_csrfToken')) {
    //         $this->getSession()->set($widgetId.'_csrfToken', $this->getSession()->createCsrfToken($widgetId));
    //     }
    // }

    public function collectAndSetPostRequests()
	{
        $submitted = false;
        // dump($_POST);
		foreach($_POST as $key => $value) {
            $underscorePos = strpos($key, '_');
            if ($underscorePos !== false) {
                $submitted = true;
            }
			if (!is_array($value)) {
                $this->getKernelObject('Request')->set($key, $this->secureValue($key, $value));
			} else {
                $this->getKernelObject('Request')->set($key, $this->secureArray($key, $value));
			}
		}
        $this->getKernelObject('Request')->setSubmitted($submitted ? true : false);
        unset($_POST);
	}

    public function secureArray($formId, $array, $keys = [], $level = 0, &$collection = array())
	{
        foreach ($array as $key => $value) {
            if (!isset($keys[$level])) {
                $keys[] = $key;
            } else {
                $keys[$level] = $key;
            }
            if (is_array($value)) {
                $collectionPart = $this->secureArray($formId, $value, $keys, ($level + 1), $collection);
            } else {
                $value = $this->secureValue($key, $value);
                $collectionPart = BasicUtils::addArrayLevels($keys, $value);
                $collection = array_replace_recursive($collection, $collectionPart);
            }
        }
        if ($level != 0) {
            return;
        }
        return $collection;
    }

    public function secureValue($key, $value)
	{
        $requestSecurity = $this->getContainer()->getKernelObject('RequestSecurity');
        $value = $requestSecurity->secureRequest($key, $value);
        $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
        return $value;
    }

    // public function secureInputAndSetValue($key)
	// {
    //     $value = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
    //     $value = $this->extraProtectValue($value);
    //     // $value = str_replace('&#13;&#10;', '\nl', $value);
    //     // $value =  preg_replace('/\v+|\\\r\\\n/Ui','<br />', $value);
    //     $this->getKernelObject('Request')->set($key, $value);
    //     $parts = explode('_', $key);
    // }

    // public function collect($array) {
    //     foreach($array as $part) {
    //         yield $part;
    //         $childs = $this->categories_model->get_by(array('parent_id' => $part));
    //         foreach(collect(array_column($childs, 'id')) as $part) {
    //             yield $part;
    //         }
    //     }
    // }

    // function delete( ){
    //     $ids = collect($this->input->post('checked'));
    // }
}
