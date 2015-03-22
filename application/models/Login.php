<?php

class Application_Model_Login
{

protected $_name = "users";
 function viewlogin()
    {
      return $this->fetchAll()->toArray[0]();  
    }
}

