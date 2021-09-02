<?php

namespace lorenzofk\Observable;

use Exception;

trait Observable
{
    public static function bootObservable()
    {
        $observerClass = static::resolveObserverClassName();

        if (empty($observerClass)) {
            return;
        }

        static::observe($observerClass);
    }

    protected static function resolveObserverClassName()
    {
        $model = new static();
        
        static::validateObserverClasses($model->observer);

        return $model->observer;
    }

    protected static function validateObserverClasses($classes)
    {
        if (is_null($classes)) {
            throw new Exception('Please make sure the $observer property is defined in your model.');
        }

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
