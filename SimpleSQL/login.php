<?php

function waf($id) {
$id= preg_replace('/or/',"", $id);
$id= preg_replace('/[--]/',"", $id);
$id= preg_replace('/and/i',"", $id);
$id= preg_replace('/[=]/',"", $id);
$id= preg_replace('/[+]/',"", $id);
$id= preg_replace('/[\s]/',"", $id);
return $id;

}
if (isset($_POST['usr']) && isset($_POST['pwd'])) {
    $servername = "localhost";
    $username = "guest";
    $password = "";
    $dbname = "simplesql";
    $usr = $_POST['usr'];
    $pwd = $_POST['pwd'];
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("connet error: " . $conn->connect_error);
    }
    $usr = waf($usr);
    $pwd = waf($pwd);
    $sql = "select * from dalao where usr = '$usr' and pwd = '$pwd'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "Name is " . $row["usr"];
            echo "<br>";
            echo "Password is " . $row["pwd"];
            echo "<br>";
        }
    } else {
        echo "error " . $conn->errno . " : " . $conn->error;
    }
    $conn->close();
}
?>


