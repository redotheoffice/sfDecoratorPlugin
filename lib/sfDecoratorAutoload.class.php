<?php

/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * defines an autoloader which automatically decorates existing classes
 * very useful to extend existing symfony classes
 *
 * @package default
 * @author The Young Shepherd
 **/
class sfDecoratorAutoload
{  
  const
    EXTENSION = "Decorator";

  static protected
    $instance = null,
    $configuration = null;

  protected
    $registered = false,
    $reloaded   = false;

  /**
   * Returns the singleton autoloader.
   * 
   * @return sfAutoloadAgain
   */
  static public function getInstance()
  {
    if (null === self::$instance)
    {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   * Constructor.
   */
  protected function __construct()
  {
  }

  /**
   * Reloads the autoloader.
   * 
   * @param  string $class
   * 
   * @return boolean
   */
  public function autoload($class)
  {
    if (substr($class, -strlen(self::EXTENSION)) == self::EXTENSION)
    {
      // this is a request for a decorator class
      // the class does not exist yet (otherwise it would have been loaded by sfSimpleAutoload)
      // so generate the class first and then include it
      // TODO: make a log entry that the decorator is generated
      $decoratedClass = substr($class, 0, strlen($class) - strlen(self::EXTENSION));
      if (class_exists($decoratedClass))
      {
        $generatorManager = new sfGeneratorManager(self::$configuration);
        eval($generatorManager->generate('sfDecoratorGenerator', array(
          'decorated_class' => $decoratedClass,
          'generated_class' => $class
        )));
        
        return true;
      }
    }
    return false;
  }

  /**
   * Returns true if the autoloader is registered.
   * 
   * @return boolean
   */
  public function isRegistered()
  {
    return $this->registered;
  }

  /**
   * Registers the autoloader function.
   */
  public function register(sfProjectConfiguration $configuration)
  {
    if (!$this->isRegistered())
    {
      self::$configuration = $configuration;
      spl_autoload_register(array($this, 'autoload'));
      $this->registered = true;
    }
  }

  /**
   * Unregisters the autoloader function.
   */
  public function unregister()
  {
    spl_autoload_unregister(array($this, 'autoload'));
    $this->registered = false;
  }
}
