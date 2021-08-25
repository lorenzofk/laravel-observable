<?php

namespace lorenzofk\Observable;

use ReflectionClass;
use ReflectionException;
use Exception;

trait Observable
{
    public static function bootObservable()
    {
        $observerClass = static::getOrGuessObserverClassName();

        if (empty($observerClass)) {
            return;
        }

        static::observe($observerClass);
    }

    protected static function getOrGuessObserverClassName()
    {
        $model = new ReflectionClass(self::class);

        try {
            $observerProperty = $model->getProperty('observer');

            if ($observerProperty->isPrivate() || $observerProperty->isProtected()) {
                $observerProperty->setAccessible(true);
            }

            $observerClasses = $observerProperty->getValue(new static);
        } catch (ReflectionException) {
            $observerClasses = 'App\\Observers\\'.$model->getShortName().'Observer';
        }

        static::checkIfClassesExist($observerClasses);

        return $observerClasses;
    }

    protected static function checkIfClassesExist($classes)
    {
        if (is_string($classes) && ! class_exists($classes)) {
            throw new Exception("The Observable class [{$classes}] does not exists.");
        }

        foreach ($classes as $class) {
            if (! class_exists($class)) {
                throw new Exception("The Observable class [{$classes}] does not exists.");
            }
        }
    }
}
