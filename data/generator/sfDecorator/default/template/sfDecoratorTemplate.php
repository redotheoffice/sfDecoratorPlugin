[?php

/**
 * Decorator to class <?php echo $this->decoratedClass ?>, automatically generated by sfDecorator
*/
abstract class <?php echo $this->generatedClass ?> extends <?php echo $this->decoratedClass ?>

{
  protected
    $decoratedObject = null;
  
  public function __construct(<?php echo $this->decoratedClass ?> $object)
  {
    $this->setDecoratedObject($object);
  }

  public function getDecoratedObject()
  {
    return $this->decoratedObject;
  }
  
  public function setDecoratedObject(<?php echo $this->decoratedClass ?> $object = null)
  {
    $this->decoratedObject = $object;
  }

<?php echo sfDecorator::getMethodCodeFor($this->decoratedClass) ?>
  
  public function __clone()
  {
    $this->decoratedObject = clone($this->decoratedObject);
  }
}
