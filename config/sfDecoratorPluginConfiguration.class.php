<?php

/**
* sfDecoratorPluginConfiguration class
*/
class sfDecoratorPluginConfiguration extends sfPluginConfiguration
{
  public function configure()
  {
    if (class_exists('sfDecoratorAutoload'))
    {
      // sfPluginConfiguration::configure is run several times each request, the first time
      // the sfAutoloaderSimple has not been started, so only when sfDecoratorAutoload comes
      // available, it should be registered
      sfDecoratorAutoload::getInstance()->register($this->configuration);
    }
  } 
}
