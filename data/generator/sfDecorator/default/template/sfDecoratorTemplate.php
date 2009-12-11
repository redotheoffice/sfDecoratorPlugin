[?php

/**
 * Decorator to class <?php echo $this->decoratedClass ?>, automatically generated by sfDecorator
*/
abstract class <?php echo $this->generatedClass ?> extends <?php echo $this->decoratedClass ?>

{
  protected
    $object = null;
  
  function __construct(<?php echo $this->decoratedClass ?> $object)
  {
    $this->object = $object;
  }

<?php echo sfDecorator::getMethodCodeFor($this->decoratedClass) ?>
  
  function __clone()
  {
    $this->object = clone($this->object);
  }
}