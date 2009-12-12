<?php

/**
* sfDecoratorPluginConfiguration class
*/
class sfDecoratorPluginConfiguration extends sfPluginConfiguration
{
  public function configure()
  {
    // class might not be loaded yet, force the load
    require_once(dirname(__FILE__).'/../lib/sfDecoratorAutoload.class.php');
    
    sfDecoratorAutoload::getInstance()->register($this->configuration);
  } 
}
