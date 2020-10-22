<?php
show_source(__FILE__);
include ("flag.php");
$a = $_GET['a'];
$b = $_GET['b'];
$c = $_POST['c'];
if (isset($a) & isset($b)) {
    if (ctype_alpha($a) & is_numeric($b) & md5((string)($a)) == md5((string)(($b)))) {
        if (isset($c)) {
            $c = json_decode($c);
            if ($c->key == $flag) {
                echo $flag;
            } else {
                echo 'Wrong c.<br>';
            }
        } else {
            echo 'give me a c.<br>';
        }
    } else {
        echo 'Wrong a or b.<br>';
    }
} else {
    echo 'give me a & b.<br>';
}
?>
