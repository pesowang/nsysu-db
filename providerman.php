<!doctype html>
<html lang="en">
  <head>
  <?php session_start();  // 啟動交談期 ?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>供應商 | 管理頁</title>
  </head>
  <body>

<!-- 資料新增成功通知 -->
<script>
<?php if (isset($_SESSION["provider_add"])) { ?>
  swal("資料新增成功!", "","success")
  <?php } unset($_SESSION["provider_add"]) ?>
</script>

<!-- 資料刪除成功通知 -->
<script>
<?php if (isset($_SESSION['del'])) { ?>
  swal("該筆資料已成功刪除!", "","success")
  <?php } unset($_SESSION['del']) ?>
</script>

<header>
  <!-- 導覽列 -->
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
   <div class="container-fluid">
      <a class="navbar-brand" href="providerman.php">
        <img src="https://lh3.googleusercontent.com/liJ9HfOMOtuQvw57oTjLy29pJuXMAcf7lE-rLYc4vB4kQZ0kuIZwNUfdwrY-hhSZQOOIifNTdCsW52TsosQVUA1c_2TG3tf4iCB4dgDLkb__iJ7L3pZtcp2bFdQ39t05-bw5pnQ9=w400" alt="" height="30" class="d-inline-block align-text-top">
        產銷履歷-供應商維護系統
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <ul class="navbar-nav me-auto mb-2 mb-lg-0">
         <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="casearch.php">批號查詢</a>
         </li>
         <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
               後台管理
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <?php if (isset($_SESSION['provider_login'])){?>
                <li><a class="dropdown-item" href="provideradd.php">新增農產品</a></li>
                <li><a class="dropdown-item" href="#">供應商管理</a></li>
                <?php }else{ ?>
                <li><a class="dropdown-item" href="providersign.php">供應商</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="cdepsign.php">檢驗機構</a></li>
                <?php } ?>
            </ul>
         </li>
         </ul>
         <?php if (isset($_SESSION['provider_login'])){
           $conn = oci_connect("Group9", "groupp9", "140.117.69.58/orcl.cm.nsysu.edu.tw",'AL32UTF8');
           $querylogin = "SELECT * FROM PROVIDER WHERE FID='".$_SESSION["provider_login"]."'";
           $resultlogin = oci_parse($conn, $querylogin);
           oci_execute($resultlogin);
           while (($rowlog = oci_fetch_array($resultlogin, OCI_ASSOC)) != false){ //取得登入者名稱
             $loginperson = $rowlog['PERSON'];
             $loginname = $rowlog['NAME'];
           }?>
          <span class="navbar-text">
            <?php echo $loginperson."，歡迎您!";
            $_SESSION["provider_person"] = $loginperson;
            $_SESSION["provider_name"] = $loginname;
            ?>
          </span>
          <a class="nav-link active" aria-current="page" href="logout.php"><button type="button" class="btn btn-outline-warning">登出</button></a>
         <?php }else{ ?>
          <span class="navbar-text" style="color:red; font-weight:700;">
            尚未登入系統
          </span>
          <a class="nav-link active" aria-current="page" href="providersign.php"><button type="button" class="btn btn-outline-danger">登入</button></a>
         <?php } ?>
      </div>
   </div>
   </nav>
</header>
<main>
<?php
  if (isset($_SESSION["provider_login"]))
  {?>
<?php/*
$username = "Group9";
$password = "groupp9";
$ip = "140.117.69.58/orcl.cm.nsysu.edu.tw";
$connection = oci_connect($username,$password,$ip,'AL32UTF8') or die(oci_error());
if(!$connection){
	echo "資料庫連線失敗";
}else{
	echo "資料庫連線成功";
}*/

//INSERT INTO "GROUP9"."LOCATION" (LID, AREA, REGION) VALUES ('13', '苗栗', '大湖')
?>
<?php
// Create connection to Oracle

// 取得URL參數的頁數
//$query = 'SELECT * FROM "LOCATION" ORDER BY "LID" ASC';
$query = 'SELECT P.PID, P.PNAME, P.DEADLINE, P.WEIGHT, P.COUNT, L.AREA, L.REGION, PR.MDATE, C.CDATE, CA.CNAME
          FROM PRODUCT P
          LEFT JOIN PRODUCE PR ON (P.PID = PR.PID)
          LEFT JOIN LOCATION L ON (P.LID = L.LID)
          LEFT JOIN CERTIFICATE C ON (P.PID = C.PID)
          LEFT JOIN CATEGORY CA ON (P.CID = CA.CID)
          WHERE PR.FID = '.$_SESSION["provider_login"].'
          ORDER BY P.PID DESC';
//echo "QUERY：".$query;
$result = oci_parse($conn, $query);
oci_execute($result);
$total_fields=oci_num_fields($result); // 取得欄位數
?>
<div class="container">

<!-- Three columns of text below the carousel -->
<div class="row">
<h1 style="text-align:center; margin-top: 100px;margin-bottom: 20px; font-weight:700">送驗清單</h1>
<table class="table table-hover" style="text-align:center;">
  <thead>
    <tr>
      <th scope="col">編號</th>
      <th scope="col">品名</th>
      <th scope="col">種類名</th>
      <th scope="col">保存期限(天)</th>
      <th scope="col">單品重量(g)</th>
      <th scope="col">數量</th>
      <th scope="col">總重(kg)</th>
      <th scope="col">產地</th>
      <th scope="col">生產日期</th>
      <th scope="col">驗證狀態</th>
      <th scope="col">功能</th>
    </tr>
  </thead>
  <tbody>
   <?php
   while (($row = oci_fetch_array($result, OCI_ASSOC)) != false){
    if(!isset($row['CDATE'])) $row['CDATE']="";
  ?>
    <tr>
      <th scope="row" valign="middle"><?php echo $row['PID'] ?></th>
      <td valign="middle"><?php echo $row['PNAME'] ?></td>
      <td valign="middle"><?php echo $row['CNAME'] ?></td>
      <td valign="middle"><?php echo $row['DEADLINE'] ?></td>
      <td valign="middle"><?php echo $row['WEIGHT'] ?></td>
      <td valign="middle"><?php echo $row['COUNT'] ?></td>
      <td valign="middle"><?php echo $row['COUNT']*$row['WEIGHT']/1000 ?></td>
      <td valign="middle"><?php echo $row['AREA']." ".$row['REGION'] ?></td>
      <td valign="middle"><?php echo $row['MDATE'] ?></td>
      <td valign="middle"><?php if ($row['CDATE']=="0"){
        echo "❌ 不通過";
      }elseif (empty($row['CDATE'])){
        echo "尚未驗證";
      }else{
        echo "✔️ 驗證日：".$row['CDATE'];
      } ?></td>
      <td><?php 
      if ($row['CDATE']=="0"){
        echo  "不可修改";
      }elseif (empty($row['CDATE'])){
        $pid = $row['PID'];?>
        <div class="btn-group" role="group" aria-label="Basic outlined example">
          <button type="button" class="btn btn-outline-primary" onclick="location.href='provideredit.php?action=edit&id=<?php echo $pid ?>'">編輯</button>
          <button type="button" class="btn btn-outline-danger" onclick="del<?php echo $pid ?>()">刪除</button>
        </div>
      </td>
      <script>
				function del<?php echo $pid ?>() {
				  let text = "！！重要警告！！\n您確定要刪除該會員的資料嗎？";
				  if (confirm(text) == true) {
					location.href = 'provideredit.php?action=del&id=<?php echo $pid ?>';
				  }
				}
			</script>
        <?php
/*
        echo "<a href='provideredit.php?action=del&id=";
        echo $row['PID']."'><b>刪除</b>";*/
      }else{
        echo "不可修改";
      }
       ?></td>
    </tr>
    <?php 
   }?>
  </tbody>
</table>
</div><!-- /.row -->

<!-- /END THE FEATURETTES -->

</div><!-- /.container -->

<?php }else{ ?>
      <div class="container">
        <center>
          <h3 style="margin-top: 100px; margin-bottom: 80px; font-weight:700; color:red;">您尚未登入系統<br>請您點選下方按鈕進行登入，完成登入後即可進行操作。</h3>
          <a href="providersign.php"><button type="button" class="btn btn-outline-danger" style="margin-bottom: 550px;">前往登入頁</button></a>
        </center>
      </div>

      <?php
  }
?>


  <!-- FOOTER -->
  <footer class="container navbar-fixed-bottom">
    <hr class="featurette-divider">
    <p class="float-end"><a href="#"><img src="https://img.icons8.com/flat-round/344/collapse-arrow--v1.png" style="height:20px;"></a></p>
    <p>&copy; 國立中山大學 NSYSU, 資管所 DBProject_Group 9</p>
  </footer>
</main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  </body>
</html>