<?php

namespace lorenzofk\Observable;

use ReflectionClass;
use ReflectionException;
use Exception;

trait Observable
{
    public static function bootObservable()
    {
        $observerClass = static::getObserverClassName();

        if (empty($observerClass)) {
            return;
        }

        static::observe($observerClass);
    }

    protected static function getObserverClassName()
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

        static::validateObserverClasses($observerClasses);

        return $observerClasses;
    }

    protected static function validateObserverClasses($classes)
    {
        if (! is_string($classes) && ! is_array($classes)) {
            throw new Exception('The Observer class must be an array or a string.');
        }

        if (is_array($classes)) {
            foreach ($classes as $class) {
                if (! class_exists($class)) {
                    throw new Exception("The Observer class [{$class}] does not exists.");
                }
            }
        }

        if (! class_exists($classes)) {
            throw new Exception("The Observer class [{$classes}] does not exists.");
        }
    }
}
