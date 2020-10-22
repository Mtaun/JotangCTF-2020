<?php
show_source(__FILE__);
include ("flag.php");
$a=$_GET['a'];
$b=$_POST['b'];
if(isset($a)){
	if(isset($b)){
		echo $flag;
	}
}
?>