<?php

namespace lorenzofk\Observable\Tests;

use Exception;
use Illuminate\Database\Eloquent\Model;
use lorenzofk\Observable\Observable;

class ObservableTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_if_the_model_observer_property_is_not_defined()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Please make sure the $observer property is defined in your model.');
        
        new class extends Model {
            use Observable;
        };
    }

    /** @test */
    public function it_throws_an_exception_if_the_model_observer_property_is_not_an_array_or_a_string()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The Observer class must be an array or a string.');
        
        new class extends Model {
            use Observable;

            protected $observer = 1;
        };
    }

    /** @test */
    public function it_throws_an_exception_if_the_model_observer_class_does_not_exist()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The Observer class [InvalidClass] does not exists.');
        
        new class extends Model {
            use Observable;

            protected $observer = 'InvalidClass';
        };
    }

    /** @test */
    public function it_throws_an_exception_if_the_one_of_the_model_observer_classes_does_not_exist()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The Observer class [InvalidClass] does not exists.');

        new class extends Model {
            use Observable;

            protected $observer = [ModelObserver::class, 'InvalidClass'];
        };
    }

    /** @test */
    public function it_should_perform_an_observer_action_after_creating_a_new_model()
    {
        $model = new class extends Model {
            use Observable;

            protected $table = 'new_test_table';
            protected $observer = ModelObserver::class;
        };

        $model->name = 'name';
        $model->save();

        $model->fresh();

        $this->assertEquals(strtoupper('name'), $model->name);
    }
}

class ModelObserver
{
    public function created(Model $model)
    {
        $model->name = strtoupper($model->name);
        $model->save();
    }
}