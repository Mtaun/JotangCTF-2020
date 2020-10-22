<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<!-- 如果你是零基础，我建议先手动注入，而不是依靠各种工具 :) -->
<form method="get">
    阅读: <input type="text" name="inject" value="1">
    <input type="submit">
</form>
<pre>
<?php
if (isset($_GET['inject'])) {
  $servername = "localhost";
  $username = "guest";
  $password = "";
  $dbname = "easysql";
  $id = $_GET['inject'];
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
      die("连接失败: " . $conn->connect_error);
  }
  $sql = "select * from poetry where id = $id;";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
      // 输出数据
      while ($row = $result->fetch_assoc()) {
          echo $row["verse"] . "<br>";
      }
  } else {
      echo "error " . $conn->errno . " : " . $conn->error;
  }
  $conn->close();  
}
?>
</pre>
</body>
</html>