<?php

namespace App;

class Container
{
    private static $poolClasses = [];

    public static function getInstance($obj)
    {
        try {
            $class = new \ReflectionClass($obj);
        } catch (\ReflectionException $e) {
            throw new \Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        $className = $class->getName();
        if (!isset(self::$poolClasses[$className])) {
            if ($obj instanceof $className) {
                self::$poolClasses[$className] = $obj;
            } else {
                self::$poolClasses[$className] = new $className;
            }
        }
        return self::$poolClasses[$className];
    }

    public static function runMethod($class, string $method = "__toString", array $attributes = []): void
    {
        try {
            $reflectionClass = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new \Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        $m = $reflectionClass->getMethod($method);
        $parameters = $m->getParameters();
        $call_parameters = [];
        foreach ($parameters as $parameter) {

            $paramName = $parameter->getName();
            if (isset($attributes[$paramName])) {
                $call_parameters[] = $attributes[$paramName];
                continue;
            }

            $paramType = $parameter->getType();
            if ($paramType == "") {
                $call_parameters[] = NULL;
                continue;
            }
            $paramType = $parameter->getType()->getName();

            if (in_array($paramType, ['string', 'int', 'bool', 'array'])) {
                $var = null;
                settype($var, $paramType);
                $call_parameters[] = $var;
                continue;
            }

            try {
                $obj = self::getInstance($paramType);
            } catch (\Exception $e) {
                throw $e;
            }

            $call_parameters[] = $obj;
        }
        //var_dump($call_parameters);
        $obj = new $class;
        call_user_func_array([$obj, $method], $call_parameters);
    }
}
