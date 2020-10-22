<?php

class MyDirectory {
    public $name; 
    public function __construct($name) {
        $this->name = $name;
    }
    public function __toString(){
        $num = count(scandir($this->name)); 
        if($num > 0){
            echo "count $num files"; 
        } else {        
            echo "flag path is /app/flag-{{md5}}"; 
            //only 8 bit
            //only num and lower case letters
            //flag filename is flag.php
        }
    }
}
 
class MyFile {
    public $name;
    public $user;
    public function __construct($name, $user) {
        $this->name = $name;
        $this->user = $user; 
    }
    public function __toString(){
        return file_get_contents($this->name);
    }

    public function __wakeup(){
        if(stristr($this->name, "flag")!==False) 
            $this->name = "/app/first";
        else
            $this->name = "/app/second"; 
        if(isset($_GET['user'])) {
            $this->user = $_GET['user']; 
        }
    }
    public function __destruct() {
        echo $this; 
    }
}
 
if(isset($_GET['input'])){
    $input = $_GET['input']; 

    if(stristr($input, 'user')!==False){
        die('Hacker'); 
    } else {
        unserialize($input);
    }
}else { 
    highlight_file(__FILE__);
}
?>