<?php

/**
 * Decorator generator.
 *
 * This class generates decorators for classes given
 */
class sfDecoratorGenerator extends sfGenerator
{
  /**
   * Initializes the current sfGenerator instance.
   *
   * @param sfGeneratorManager A sfGeneratorManager instance
   */
  public function initialize(sfGeneratorManager $generatorManager)
  {
    parent::initialize($generatorManager);

    $this->setGeneratorClass('sfDecorator');
  }

  /**
   * Generates classes and templates in cache.
   * Usable parameters are
   * - decorated_class required Defines the class to be decorated
   * - generated_class required The name of the generated decorator class
   * - file_format optional format of the filename of the file, default %s.class.php
   * - path optional location of the generated decorator to be saved, default \cache\decorator
   *
   * @param array The parameters
   *
   * @return string The data to put in configuration cache
   */
  public function generate($params = array())
  {
    if (!isset($params['decorated_class'], $params['generated_class']))
    {
      throw new InvalidArgumentException('To generate a decorator the \'decorated_class\' and \'generated_class\' parameters are required');
    }

    $this->decoratedClass = $params['decorated_class'];
    
    if (!class_exists($this->decoratedClass))
    {
      throw new RuntimeException('Cannot create decorator for non existing class \''.$this->decoratedClass.'\'');
    }
    
    $this->generatedClass = $params['generated_class'];

    if (class_exists($this->generatedClass))
    {
      throw new RuntimeException('Cannot redeclare decorator as the class already exists \''.$this->generatedClass.'\'');
    }

    $path = isset($params['path']) ? $params['path'] : sfConfig::get('sf_cache_dir') . '/decorator';
    if (!is_dir($path))
    {
      mkdir($path, 0777, true);
    }
    
    $fileFormat = isset($params['file_format']) ? $params['file_format'] : '%s.class.php';
    
    file_put_contents($path.'/'.sprintf($fileFormat, $this->generatedClass), $this->evalTemplate('sfDecoratorTemplate.php'));
    
    $conf = $this->getGeneratorManager()->getConfiguration();
    if ($conf instanceof sfApplicationConfiguration && !$conf->isDebug())
    {
      //register the generated class in the autoloader
      sfSimpleAutoload::getInstance()->setClassPath($this->generatedClass, $path.'/'.sprintf($fileFormat, $this->generatedClass));
    }
    
    return "require_once('".$path.'/'.sprintf($fileFormat, $this->generatedClass)."');";
  }
}
