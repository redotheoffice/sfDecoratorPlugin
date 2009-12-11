<?php
/**
 * generateDecorator writes a class which decorates another, with FULL api
 * forwarding
 *
 * @package default
 * @author The Young Shepherd
 **/
class sfGenerateDecoratorTask extends sfBaseTask
{  
  protected function configure()
  {
    $this->namespace = 'generate';
    $this->name = 'decorator';
    $this->briefDescription = 'Creates a decorator class around another class';
    $this->detailedDescription = <<<EOF
The Symfony framework consists of lots of small and bigger classes. Most of the
time it works great out of the box, sometimes it needs a little more tweaking.
The decorator pattern is one of the tools of doing this. This 
[generate:decorater|INFO] task analyses the class you want to decorate and 
creates a decorating class for you which forwards all public properties of the
decorated class.

Call it with:
[php symfony generate:decorator class_to_decorate class_to_create path|INFO]

Sample:
[php symfony generate:decorator sfForm sfFormSerializable|INFO] creates a new
[sfFormSerializable.class.php|INFO] in your lib directory, which decorates the
[sfForm|INFO] class.
EOF;
    $this->addArgument('classToDecorate', sfCommandArgument::REQUIRED, 'Name of the class to decorate');
    $this->addArgument('classToCreate', sfCommandArgument::OPTIONAL, 'Name of the class to create, defaults to decorated class with \'Decorator\' appended');
    $this->addArgument('path', sfCommandArgument::OPTIONAL, 'Path where the class is to be saved, defaults to your lib dir', sfConfig::get('sf_lib_dir'));
  }

  protected function execute($arguments = array(), $options = array())
  {
    $params = array(
      'decorated_class' => $arguments['classToDecorate'],
      'generated_class' => (null === $arguments['classToCreate'] ? $arguments['classToDecorate'].'Decorator' : $arguments['classToCreate']),
      'path'            => $arguments['path']
      );

    $generatorManager = new sfGeneratorManager($this->configuration);
    $generatorManager->generate('sfDecoratorGenerator', $params);
    $this->logSection('generate',sprintf('%s created as a decorator to %s in %s', $params['generated_class'], $params['decorated_class'], $params['path']));
  }
} // END class generateDecorator
