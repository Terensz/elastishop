<?php

use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\kernel\base\Reflector;
use framework\component\parent\Rendering;

if (!function_exists('dumpNoHeader'))
{
	function dumpNoHeader($data = null, $title = null, $output = 'screen')
	{
		$callerClass = '*None*';
		$callerFunction = '';
		$trace = debug_backtrace();
		$current = $trace[0];
		$file = BasicUtils::explodeAndGetElement($current['file'], '\\', 'last');
		$serverPath = BasicUtils::explodeAndRemoveElement(getcwd(), '/', 'last');
		$file = str_replace($serverPath, '', $file);
		$line = $current['line'];
		$caller = $trace[1];
		if (isset($caller['class'])) {
			$callerClass = BasicUtils::explodeAndGetElement($caller['class'], '\\', 'last');
		}
		if (isset($caller['function'])) {
			$callerFunction = $caller['function'];
		}

		$dumper = new Dumper();
		return $dumper->display($data);
	}
}

if (!function_exists('dump'))
{
	function dump($data = null, $output = 'screen')
	{
		$callerClass = '*None*';
		$callerFunction = '';
		$trace = debug_backtrace();
		$current = $trace[0];
		$file = BasicUtils::explodeAndGetElement($current['file'], '\\', 'last');
		$serverPath = BasicUtils::explodeAndRemoveElement(getcwd(), '/', 'last');
		$file = str_replace($serverPath, '', $file);
		$line = $current['line'];
		$caller = $trace[1];
		if (isset($caller['class'])) {
			$callerClass = BasicUtils::explodeAndGetElement($caller['class'], '\\', 'last');
		}
		if (isset($caller['function'])) {
			$callerFunction = $caller['function'];
		}

		$dumper = new Dumper();
		return $dumper->display($data, array(
			'file' => $file,
			'line' => $line,
			'callerClass' => $callerClass,
			'callerFunction' => $callerFunction
		), $output);
	}
}

class Dumper extends Rendering
{
    private $elementTypeKeyColorsHtml = [
        'array' => '59acd9',
        'object' => 'bd87bc',
        'property' => 'e5a8e4',
        'string' => '64e14f',
    ];

    private $elementTypeValueColorsHtml = [
        'array' => '59acd9',
        'object' => '9d419c',
        'property' => 'c766c6',
        'string' => '64e14f',
        'nullbool' => 'e2b988',
        'int' => '5d78b5'
    ];

    private $elementTypeKeyColorsCli = [
        'array' => '0;34m',
        'object' => '0;31m',
        'property' => 'e5a8e4',
        'string' => '1;32m',
    ];

    private $elementTypeValueColorsCli = [
        'array' => '1;34m',
        'object' => '1;31m',
        'property' => '1;31m',
        'string' => '0;32m',
        'nullbool' => '0;33m',
        'int' => '0;34m'
    ];

	private $mode = 'web';
	private $blocks = array();
	private $objects = array();

	public function getBlocks()
	{
		return $this->blocks;
	}

	public static function isCLICall()
	{
        return (php_sapi_name() == "cli") ? true : false;
	}

	public function display($data, $params = null, $output = 'screen')
	{
		if (self::isCLICall() && $output == 'screen') {
			$this->mode = 'CLI';
		}
		if ($output == 'return') {
			$this->mode = 'minimal';
		}

		if (!is_array($data) && !is_object($data)) {
			$this->dumpString($data);
		} else {
			$this->initDump($data);
			// $this->rearrangeBlocks();
			// $this->echoBlocks('<br>');
			$this->processBlocks();
		}

		if ($this->mode == 'minimal') {
			$result = '';
			if ($params) {
				$result .= "======================================= \n";
				$result .= "* File: ".$params['file']." \n";
				$result .= "* Line: ".$params['line']." \n";
				$result .= "* Class: ".$params['callerClass']." \n";
				$result .= "* Method: ".$params['callerFunction']." \n";
				$result .= "======================================= \n";
			}

			foreach ($this->blocks as $block) {
				$result .= str_repeat("  ", $block['tab']).$block['wrappedKey'].$block['wrappedValue']." \n";
			}
			return $result;
			// var_dump($this->blocks);
		}

		if ($this->mode == 'CLI') {
			if ($params) {
				echo "======================================= \n";
				echo "* File: ".$params['file']." \n";
				echo "* Line: ".$params['line']." \n";
				echo "* Class: ".$params['callerClass']." \n";
				echo "* Method: ".$params['callerFunction']." \n";
				echo "======================================= \n";
			}

			foreach ($this->blocks as $block) {
				echo str_repeat("  ", $block['tab']).$block['wrappedKey'].$block['wrappedValue']." \n";
			}
			// var_dump($this->blocks);
		}
		
		if ($this->mode == 'web' && !$params) {
			$renderedView = $this->renderView(
				FileHandler::completePath('framework/kernel/debug/Dumper/view/displayDumpNoHeader.php'),
				array(
					'blocks' => $this->blocks
				)
			);
			if ($output == 'return') {
				return $renderedView;
			}
			// var_dump($this->blocks);
			echo $renderedView;
		}
		
		if ($this->mode == 'web' && $params) {
			$renderedView = $this->renderView(
				FileHandler::completePath('framework/kernel/debug/Dumper/view/displayDump.php'),
				array(
					'blocks' => $this->blocks,
					'file' => $params['file'],
					'line' => $params['line'],
					'callerClass' => $params['callerClass'],
					'callerFunction' => $params['callerFunction'],
					'output' => $output
				)
			);
			if ($output == 'return') {
				return $renderedView;
			}
			// var_dump($this->blocks);
			echo $renderedView;
		}
	}

	public function setValue($blockId, $value)
	{
		for ($i = 0; $i < count($this->blocks); $i++) {
			if ($this->blocks[$i]['blockId'] == $blockId) {
				$this->blocks[$i]['value'] = $value;
			}
		}
	}

	public function processBlocks()
	{
		$tabOfParents = array();
		for ($i = 0; $i < count($this->blocks); $i++) {
			if (!isset($tabOfParents[$this->blocks[$i]['parentBlockId']])) {
				$tabOfParents[$this->blocks[$i]['parentBlockId']] = $this->getTabOfBlock($this->blocks[$i]['parentBlockId']) + 1;
			}
			$this->blocks[$i]['tab'] = $tabOfParents[$this->blocks[$i]['parentBlockId']];
		}
	}

	public function getTabOfBlock($blockId)
	{
		foreach ($this->blocks as $block) {
			if ($block['blockId'] == $blockId) {
				return $block['tab'];
			}
		}
		return -1;
	}

	public function putValueToBlock($block)
	{
		$this->blocks[] = $block;
	}

	public function addBlock($block)
	{
		$this->blocks[] = $block;
	}

	public function wrapArrayHeader()
	{
		switch ($this->mode) {
			case 'web';
				return '<span style="color: #'.$this->elementTypeValueColorsHtml['array'].';">Array()</span>';
				break;
			case 'CLI';
				return "\033[".$this->elementTypeValueColorsCli['array']."Array()";
				break;
			case 'minimal';
				return "Array()";
				break;
		}
	}

	public function wrapKey($key, $blockType, $index = null, $parentType = null)
	{
		$pre = (string)$index;
		$keyPre = '';
		if ($parentType == 'array') {
			$keyPre = ($index === 0 && $key != $pre && $key ? '(0)' : (!$pre || $key == $pre ? '' : '('.$pre.')'));
		}

		switch ($this->mode) {
			case 'web';
				$innerWrap = '<span style="color: #dedede;">'.$keyPre.'[</span>'.$key.'<span style="color: #dedede;">]</span> =>';
				$styleStr = isset($this->elementTypeKeyColorsHtml[$parentType]) ? ' style="color: #'.$this->elementTypeKeyColorsHtml[$parentType].';"' : '';
				$result = '<span'.$styleStr.'>'.$innerWrap.'</span>';
				break;
			case 'CLI';
				$keyStr = isset($this->elementTypeKeyColorsCli[$parentType]) ? "\033[".$this->elementTypeKeyColorsCli[$parentType]."\033[37m[".$key."]" : "[".$key."]";
				$result = "\033[1;30m".(!empty($keyPre) ? $keyPre : "").$keyStr;
				break;
			case 'minimal';
				$result = "[".$key."]";
				break;
		}

		return $result;
	}

	public function wrapValue($value, $blockType, $parentType)
	{
		if ($value === 0) {
			$blockType = 'int';
		} elseif ($value === null || $value == 'null') {
			$blockType = 'nullbool';
			$value = 'null';
		} elseif ($value === false) {
			$blockType = 'nullbool';
			$value = 'false';
		} elseif ($value === true) {
			$blockType = 'nullbool';
			$value = 'true';
		} elseif (is_numeric($value)) {
			$blockType = 'int';
		}

		switch ($this->mode) {
			case 'web';
				$styleStr = isset($this->elementTypeValueColorsHtml[$blockType]) ? ' style="color: #'.$this->elementTypeValueColorsHtml[$blockType].';"' : '';
				$result = '<span'.$styleStr.'>'.$value.'</span>';
				break;
			case 'CLI';
				$result = isset($this->elementTypeValueColorsCli[$blockType]) ? "\033[".$this->elementTypeValueColorsCli[$blockType].$value : $value;
				break;
			case 'minimal';
				$result = $value;
				break;
		}

		return $result;
	}

	public function dumpObject($object, $key = null, $parentBlockId = null, $parentType = null, $index = null, $level = 0)
	{
		$blockId = $this->nextBlockId();
		$objectId = method_exists($object, 'getId') ? $object->getId() : null;
		$newObjectCollectionElement = array(
			'class' => get_class($object),
			'id' => $objectId,
			'object' => $object,
			'blockId' => $blockId
		);

		// dump($this->objects);exit;
		foreach ($this->objects as $objectCollectionElement) {
			if ($objectCollectionElement['object'] == $object) {
				$value = BasicUtils::explodeAndGetElement(get_class($object), '\\', 'last').' #'.$objectCollectionElement['blockId'];
				$this->addBlock(array(
					'blockId' => $blockId,
					'blockType' => 'object',
					'parentBlockId' => $parentBlockId,
					'parentType' => $parentType,
					'index' => $index,
					'key' => $key,
					'wrappedKey' => $this->wrapKey($key, 'object', $index, $parentType),
					'value' => $value,
					'wrappedValue' => $this->wrapValue($value, 'object', $parentType),
					'valueType' => 'object',
					'tab' => 0
				));
				return;
			}
		}

		$this->objects[] = $newObjectCollectionElement;

		if ($level > 20) {
			return false;
		}

		$value = BasicUtils::explodeAndGetElement(get_class($object), '\\', 'last').' #'.$blockId;
		$this->addBlock(array(
			'blockId' => $blockId,
			'blockType' => 'object',
			'parentBlockId' => $parentBlockId,
			'parentType' => $parentType,
			'index' => $index,
			'key' => $key,
			'wrappedKey' => $this->wrapKey($key, 'object', $index, $parentType),
			'value' => $value,
			'wrappedValue' => $this->wrapValue($value, 'object', $parentType),
			'valueType' => 'object',
			'tab' => 0
		));
		
		$reflector = new Reflector();
		$propertiesArray = $reflector->getProperties($object, false);

		foreach ($propertiesArray as $reflectionProperty) {
			$propertyName = is_string($reflectionProperty) || is_numeric($reflectionProperty) ? $reflectionProperty : $reflectionProperty->getName();
			$value = $reflector->getValue($object, $propertyName);	
			if (is_object($value)) {
				$this->dumpObject($value, $propertyName, $blockId, 'object', 0, ($level + 1));
			} elseif (is_array($value)) {
				$this->dumpArray($value, $propertyName, $blockId, 'object', 0);
			} else {
				$this->dumpString($value, $propertyName, $blockId, 'object');
			}
		}
	}

	public function dumpString($data, $key = null, $parentBlockId = null, $parentType = null, $index = null)
	{
		$handledData = $this->handleNullAndBool($data);
		if ($handledData != $data) {

		}
		$blockId = $this->nextBlockId();
		$this->addBlock(array(
			'blockId' => $blockId,
			'blockType' => 'string',
			'parentBlockId' => $parentBlockId,
			'parentType' => $parentType,
			'index' => $index,
			'key' => $key,
			'wrappedKey' => $this->wrapKey($key, 'string', $index, $parentType),
			'value' => $handledData,
			'wrappedValue' => $this->wrapValue($handledData, 'string', $parentType),
			'valueType' => ($handledData != $data ? 'nullbool' : (is_numeric($data) ? 'int' : 'string')),
			'tab' => 0
		));
	}

	public function dumpArray($data, $key = null, $parentBlockId = null, $parentType = null, $index = 0)
	{
		$blockId = $this->nextBlockId();
		$this->addBlock(array(
			'blockId' => $blockId,
			'blockType' => 'arrayHeader',
			'parentBlockId' => $parentBlockId,
			'parentType' => $parentType,
			'index' => $index,
			'key' => $key,
			'wrappedKey' => $this->wrapKey($key, 'arrayHeader', $index, $parentType),
			'value' => 'Array()',
			'wrappedValue' => $this->wrapArrayHeader(),
			'valueType' => 'array',
			'tab' => 0
		));
		// $index = 0;
		foreach ($data as $key => $value) {
			if (is_object($value)) {
				$this->dumpObject($value, $key, $blockId, 'array', $index);
			} elseif (is_array($value)) {
				$this->dumpArray($value, $key, $blockId, 'array', $index);
			} else {
				$this->dumpString($value, $key, $blockId, 'array', $index);
			}
			$index++;
		}
		$index = null;
	}

	public function nextBlockId()
	{
		return count($this->blocks) + 1;
	}

	public function initDump($data, $key = null, $parentBlockId = null, $parentType = null)
	{
		if (!$parentBlockId) {
			$parentBlockId = count($this->blocks) == 0 ? 0 : 1;
		}
		if (is_array($data))
		{
			$this->dumpArray($data, $key, $parentBlockId, 0);
		}
		else {
			if (is_object($data)) {
				$this->dumpObject($data, $key, $parentBlockId);
			} else {
				$this->dumpString($data, $key, $parentBlockId, $parentType);
			}
		}
	}

	public static function handleNullAndBool($data)
	{
		if ($data === null) {
			return 'null';
		}
		if ($data === false) {
			return '(bool)false';
		}
		if ($data === true) {
			return '(bool)true';
		}
		return $data;
	}
}
