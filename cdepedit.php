<html>
<?php
session_start();
$id = $_GET["id"];  // 取得URL參數的編號
$action = $_GET["action"];  // 取得操作種類
$conn = oci_connect("Group9", "groupp9", "140.117.69.58/orcl.cm.nsysu.edu.tw",'AL32UTF8');

// 執行操作
switch ($action) {
   case "update": // 更新操作    
	  $status = $_POST["CA"]; // 取得欄位資料
	  echo "狀態：".$status;
	  $sql = "UPDATE CERTIFICATE SET PID='".$id."', DID='".$_SESSION["cdep_login"]."', CDATE='".$status."' WHERE PID=".$id."";
      $stmt = oci_parse($conn, $sql);
      oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
      oci_free_statement($stmt);
	  echo "<script>window.history.go(-1); </script>";
	  break;
}?>
</html>