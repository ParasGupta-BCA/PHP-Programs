<?php
  class greeting {    // class definition
    public $str = "Hello World";   // property
    function show_greeting() {
      return $this->str;
    }
  }

  // create object from class
  $message = new greeting;   // create object
  var_dump($message);
  echo "<br>This Program is Written & executed by Paras";
?>