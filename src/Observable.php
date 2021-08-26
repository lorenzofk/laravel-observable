<?php

namespace lorenzofk\Observable;

use ReflectionClass;
use ReflectionException;
use Exception;

trait Observable
{
    public static function bootObservable()
    {
        $observerClass = static::getObserverPropertyClass();

        if (empty($observerClass)) {
            return;
        }

        static::observe($observerClass);
    }

    protected static function getObserverPropertyClass()
    {
        $model = new ReflectionClass(self::class);

        try {
            $observerProperty = $model->getProperty('observer');

            if ($observerProperty->isPrivate() || $observerProperty->isProtected()) {
                $observerProperty->setAccessible(true);
            }

            $observerClasses = $observerProperty->getValue(new static);
        } catch (ReflectionException $e) {
            throw new Exception('Please make sure the $observer property is defined in your model.');
        }

        static::checkIfTheClassesExist($observerClasses);

        return $observerClasses;
    }

    protected static function checkIfTheClassesExist($classes)
    {
        if (is_string($classes)) {
            if (! class_exists($classes)) {
                throw new Exception("The Observable class [{$classes}] does not exists.");
            }

            return $classes;
        }

        foreach ($classes as $class) {
            if (! class_exists($class)) {
                throw new Exception("The Observable class [{$class}] does not exists.");
            }
        }
    }
}
