<?php
$oldname = $_FILES["file"]["name"];
$newname = preg_replace('/.+(\.[a-z]+)$/i', md5(time()) . '\1', $oldname);
if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 204800)) {
    if ($_FILES["file"]["error"] > 0) {
        die("错误：: " . $_FILES["file"]["error"] . "<br>");
    }
    if (file_exists("upload/" . $newname)) {
        die($newname . " 文件已存在");
    }
    move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $newname);
    $alldata = file_get_contents("upload/" . $newname);
    echo $alldata;
    if (strpos($alldata, "<?php") !== false) {
        unlink("upload/" . $newname);
        die("臭弟弟，文件里写了啥呢");
    } else {
        echo "上传文件名: " . $newname . "<br>";
        echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
        echo "文件存储在: " . "upload/" . $newname . "<br>";
    }
} else {
    die("臭弟弟，不是图片不收嗷");
}
?>
