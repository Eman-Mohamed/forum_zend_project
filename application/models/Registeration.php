<?php

class Application_Model_Registeration
{
protected $_name = "users";
 function viewregister()
    {
      return $this->fetchAll()->toArray();  
    }

}

