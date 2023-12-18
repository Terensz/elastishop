<?php
namespace framework\kernel\base;

use framework\component\exception\ElastiException;

class Reflector
{
	public function getProperties($object, $predefinedOnly = true)
	{
		// $dynamicPublicProperties = $this->getDynamicPublicProperties($object) ? : array();
		$publicProperties = $this->getPublicProperties($object, $predefinedOnly) ? : array();
		$protectedProperties = $this->getProtectedProperties($object) ? : array();
		$privateProperties = $this->getPrivateProperties($object) ? : array();
		$properties = array_unique(array_merge($publicProperties, $protectedProperties), SORT_REGULAR);
		$properties = array_unique(array_merge($properties, $privateProperties), SORT_REGULAR);
		return $properties;
	}

	// public function getObjectProperties($object)
	// {
	// 	$publicProperties = $this->getPublicProperties($object) ? : array();
	// 	$protectedProperties = $this->getProtectedProperties($object) ? : array();
	// 	$privateProperties = $this->getPrivateProperties($object) ? : array();
	// 	$properties = array_unique(array_merge($publicProperties, $protectedProperties), SORT_REGULAR);
	// 	$properties = array_unique(array_merge($properties, $privateProperties), SORT_REGULAR);
	// 	return $properties;
	// }

	public function getPredefinedPropertyNames($object)
	{
		$propertyNames = array();
		foreach ($this->getProperties($object, true) as $reflectionProperty) {
			// var_dump($object);
			// var_dump($reflectionProperty);
			$propertyNames[] = is_string($reflectionProperty) ? $reflectionProperty : $reflectionProperty->name;
			// $propertyNames[] = property_exists($reflectionProperty, 'name') ? $reflectionProperty->name : $reflectionProperty;
			// $propertyNames[] = $reflectionProperty->name;
		}
		return $propertyNames;
	}

	// public function getDynamicPublicProperties($object)
	// {
	// 	$reflection = new \ReflectionObject($object);
	// 	// var_dump($reflection->getProperties(\ReflectionProperty::IS_PUBLIC));exit;
	// 	// var_dump($reflection->getProperties(\ReflectionProperty::IS_PUBLIC));exit;
	// 	return $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
	// }

	public function getPublicProperties($object, $predefinedOnly = false)
	{
		if ($predefinedOnly) {
			$reflection = new \ReflectionClass($object);
			return $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
		}
		$objectVars = get_object_vars($object);
		$result = [];
		foreach ($objectVars as $objectVar => $value) {
			$result[] = $objectVar;
		}

		return $result;
	}

	public function getProtectedProperties($object)
	{
		$reflection = new \ReflectionClass($object);
		return $reflection->getProperties(\ReflectionProperty::IS_PROTECTED);
	}

	public function getPrivateProperties($object)
	{
		$reflection = new \ReflectionClass($object);
		return $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);
	}

	public function getDefaultValue($object, $searchedProperty) {
		$reflection = new \ReflectionClass($object);
		$properties = $reflection->getDefaultProperties();
		foreach ($properties as $property => $defaultValue) {
			if ($property == $searchedProperty) {
				return $defaultValue;
			}
		}
		return null;
	}

	public function objectToString($object)
	{
		$string = '';
		$reflection = new \ReflectionClass($object);
		$properties = array_merge($reflection->getProperties(\ReflectionProperty::IS_PUBLIC), $reflection->getProperties(\ReflectionProperty::IS_PROTECTED));
		$properties = array_merge($reflection->getProperties(\ReflectionProperty::IS_PRIVATE), $properties);
		foreach ($properties as $property) {
			$getter = 'get'.ucfirst($property);
			if (method_exists($object, $getter)) {
				$value = $object->$getter();
				$string .= $property.': '.$value.'<br />';
			}
		}
		return $string;
	}

	// public function getValue($object, $key)
	// {
	// 	$property = new \ReflectionProperty(get_class($object), $key);
	// 	if (!$property->isPublic()) {
	// 		$property->setAccessile(true);
	// 	}
	// 	return $property->isStatic() ? $property->getValue() : $property->getValue($object);
	// }

	public function getValue($object, $key)
	{
		$objectVars = get_object_vars($object);
		if (isset($objectVars[$key])) {
			return $objectVars[$key];
		}

		/**
		 * Dynamic std-class properties might run into this. They have $object->$key, but isset($object->$key) is false.
		*/
		if (!isset($object->$key)) {
			$reflection = new \ReflectionClass($object);
			if (!$reflection->hasProperty($key)) {
				try {
					return $object->$key;
				} catch (\Error $e) {
					// dump($e);exit;
				} catch (ElastiException $e) {
					// dump($e);exit;
				} catch (\Exception $e) {
					// dump($e);exit;
				}
			}
		}

		try {
			$property = new \ReflectionProperty(get_class($object), $key);
			if (!$property->isPublic()) {
				$property->setAccessible(true);
			}
	
		} catch(\Exception $e) {
			// dump($e);
			// var_dump('alma!');
			// var_dump($object);
			// var_dump($key);
			// exit;
			return null;
		}

		return $property->isStatic() ? $property->getValue() : $property->getValue($object);
	}

	/**
	 * Ebbe belejavitottam: $nullable = $reflectionType instanceof \ReflectionNamedType ? $reflectionType->allowsNull() : 'alma';
	*/
	public function getPropertyReflections($object, $methodName, $propertyName)
	{
		// var_dump($object);
		// var_dump($methodName);
		if (!method_exists($object, $methodName)) {
			return false;
		}
		$reflectionMethodObject = (new \ReflectionMethod(get_class($object), $methodName));
		if (!method_exists($reflectionMethodObject, 'getParameters')) {
			return false;
		}
		// var_dump($reflectionMethodObject);
		$reflectionParameters = $reflectionMethodObject->getParameters();
		$propertyReflections = array();
		foreach ($reflectionParameters as $reflectionParameter) {
			$counter = count($propertyReflections);
			$propertyReflections[$counter]['name'] = $reflectionParameter->getName();
			$reflectionType = $reflectionParameter->getType();
			// $propertyReflections[$counter]['type'] = $reflectionType;
			// foreach ($reflectionType instanceof \ReflectionUnionType ? $reflectionType->getTypes() : [$reflectionType] as $type) {
			foreach ([$reflectionType] as $type) {
				$typeOrClass = $reflectionType instanceof \ReflectionNamedType ? $reflectionType->getName() : (string) $type;
				$typeOrClass = $typeOrClass == '' ? null : $typeOrClass;
				$nullable = $reflectionType instanceof \ReflectionNamedType ? $reflectionType->allowsNull() : true;
				$propertyReflections[$counter]['nullable'] = $nullable;
				$propertyReflections[$counter]['typeOrClass'] = $typeOrClass;
				// dump($typeOrClass);
				$isClass = false;
				if ($typeOrClass) {
					$isClass = strpos($typeOrClass, '\\') === false ? false : true;
				}
				// $isClass = strpos($typeOrClass, '\\') === false ? false : true;
				$propertyReflections[$counter]['class'] = $isClass ? $typeOrClass : false;
				$propertyReflections[$counter]['type'] = $isClass ? false : $typeOrClass;
			}
		}
		return $propertyReflections;
	}

	public function getUseStatements($entity)
	{
		$reflection = new \ReflectionClass($entity);
		$source = $this->readFileSource($reflection);
		return $this->tokenizeSource($source, $reflection);
	}

	private function readFileSource($reflection)
	{
		$file = fopen($reflection->getFileName(), 'r');
		$line = 0;
		$source = '';

		while (!feof($file)) {
			++$line;

			if ($line >= $reflection->getStartLine()) {
				break;
			}

			$source .= fgets($file);
		}

		fclose($file);
		return $source;
	}

	private function tokenizeSource($source, \ReflectionClass $reflection)
	{
		$tokens = token_get_all($source);

		$builtNamespace = '';
		$buildingNamespace = false;
		$matchedNamespace = false;

		$useStatements = [];
		$record = false;
		$currentUse = [
			'class' => '',
			'as' => ''
		];

		foreach ($tokens as $token) {
			if ($token[0] === T_NAMESPACE) {
				$buildingNamespace = true;

				if ($matchedNamespace) {
					break;
				}
			}

			if ($buildingNamespace) {

				if ($token === ';') {
					$buildingNamespace = false;
					continue;
				}

				switch ($token[0]) {

					case T_STRING:
					case T_NS_SEPARATOR:
						$builtNamespace .= $token[1];
						break;
				}

				continue;
			}

			if ($token === ';' || !is_array($token)) {

				if ($record) {
					$useStatements[] = $currentUse;
					$record = false;
					$currentUse = [
						'class' => '',
						'as' => ''
					];
				}

				continue;
			}

			if ($token[0] === T_CLASS) {
				break;
			}

			if (strcasecmp($builtNamespace, $reflection->getNamespaceName()) === 0) {
				$matchedNamespace = true;
			}

			if ($matchedNamespace) {

				if ($token[0] === T_USE) {
					$record = 'class';
				}

				if ($token[0] === T_AS) {
					$record = 'as';
				}

				if ($record) {
					switch ($token[0]) {

						case T_STRING:
						case T_NS_SEPARATOR:

							if ($record) {
								$currentUse[$record] .= $token[1];
							}

							break;
					}
				}
			}

			if ($token[2] >= $reflection->getStartLine()) {
				break;
			}
		}


		// Make sure the as key has the name of the class even
		// if there is no alias in the use statement.
		foreach ($useStatements as &$useStatement) {

			if (empty($useStatement['as'])) {

				$useStatement['as'] = basename($useStatement['class']);
			}
		}

		return $useStatements;
	}
}
