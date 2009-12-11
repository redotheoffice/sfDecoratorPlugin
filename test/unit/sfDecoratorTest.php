<?php
require_once dirname(__FILE__).'/../../../../test/bootstrap/unit.php';

$t = new lime_test(4, new lime_output_color());

/**
* Testclass for decoration
*/
class TesterClass
{
  function __construct($argument)
  {
  }
  
  public function funPublic($valuePub='')
  {
    return "FOO!";
  }
}

$t->ok(!class_exists('TesterClassDecorator'),
  "Decorator does not exist yet");

sfDecoratorAutoload::getInstance()->register($configuration);

$t->ok(class_exists('TesterClassDecorator'),
  "Decorator does exist after registering the autoloader");


class TesterClassFoo extends TesterClassDecorator
{
  public function funPublic($value='')
  {
    return $this->object->funPublic()."BAR!";
  }
}

$test = new TesterClassFoo(new TesterClass('test'));

$t->is('TesterClassFoo', get_class($test),
  "A decorator of another class can be instantiated");
  
$t->is('FOO!BAR!', $test->funPublic(),
  "A decorator can decorate the original class");