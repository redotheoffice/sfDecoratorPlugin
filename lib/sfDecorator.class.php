<?php

/**
* sfDecorator writes PHP code to generate a decorater for a class
*/
abstract class sfDecorator
{
  const
    MARKER = "(autocode by generate:decorator)",
    DOCBLOCK = "  /** forwards to class %%class%% %%marker%% */";

  /**
   * @return array key = name of method to be decorated, value = code to decorate
   * @author The Young Shepherd
   **/
  static public function getMethodsFor($className)
  {
    if (is_object($className))
    {
      $className = get_class($class);
    }

    //forward all the public methods
    $rc = new ReflectionClass($className);
    $methods = array();
    
    foreach ($rc->getMethods() as $rm)
    {
      if (  $rm->isPublic() 
        && !$rm->isStatic()
        &&  $rm->getName() != '__construct'
        &&  $rm->getName() != '__clone')
      {
        $file = file_get_contents($rm->getFileName());
        $file = explode("\n",$file);
        
        // make a docblock marker
        $docblock = strtr(self::DOCBLOCK, array(
          '%%marker%%' => self::MARKER,
          '%%class%%' => $rm->getDeclaringClass()->getName()));

        // copy the signature from the original file and make the correct function call
        $code = str_replace(array('abstract ',';'),array('',''),$file[$rm->getStartLine()-1]."\n");
        $code .= "  {\n";
        
        // return the result of the inner object
        $code .= "    \$result = \$this->object->" . $rm->getName();
        $rps = $rm->getParameters();
        $ps = array();
        foreach ($rps as $rp)
        {
          $ps[] = '$'.$rp->getName();
        }
        $code .= "(".implode(', ',$ps).");\n";
        
        $code .= "    return \$result===\$this->object ? \$this : \$result;\n";
        $code .= "  }\n";
        
        $methods[$rm->getName()] = $docblock."\n".$code;
      }
    }    
    return $methods;
  }

  /**
   * Returns code for all methods, excludes class definition, constructor and __clone
   *
   * @return string The code for all the methods which need to be forwarded
   * @author The Young Shepherd
   **/
  static public function getMethodCodeFor($className)
  {    
    return implode("\n",self::getMethodsFor($className));
  }
  
  /**
   * updateCodeFor updates the code in an existing generated decorator. Left
   * here as a reference
   **/
  /*
  abstract public function updateCodeFor($className)
  {
    //produce the code
    $filename = sfConfig::get('sf_lib_dir').DIRECTORY_SEPARATOR.$decorator.'.class.php';
    $isUpdate = file_exists($filename);
    if ($isUpdate)
    {
      // code already exists, remove all user changed code
      $code = file_get_contents($filename);
      
      //remove all previously defined 
      $lines = explode("\n", $code);
      
      $rc = new ReflectionClass($decorator);
      foreach ($rc->getMethods() as $rm)
      {
        if ($rm->isPublic() && !$rm->isStatic() && $rm->getDeclaringClass()->getName() == $decorator)
        {
          if (false === strpos($rm->getDocComment(), self::$marker))
          {
            // user defined, remove from the list of methods to insert
            unset($methods[$rm->getName()]);
          }
          else
          {
            // auto generated function, remove all of the signature from the file
            $commentLines = substr_count($rm->getDocComment(),"\n") + 1;
            for ($i=$rm->getStartLine()-$commentLines-1; $i<$rm->getEndLine(); $i++)
            {
              unset($lines[$i]);
            }
            
          }
        }
      }
      $code = implode("\n", $lines);
  }
  */
}
