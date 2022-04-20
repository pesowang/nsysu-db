<!doctype html>
<html lang="en">
  <head>
  <?php session_start();  // 啟動交談期?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>消費者 | 產品批號查詢</title>
  </head>
  <body>

<!-- 登出成功通知 -->
<script>
<?php
if (isset($_SESSION["logout"])) { ?>
  swal("您已成功登出!", "","success")
  <?php } unset($_SESSION["logout"]) ?>
</script>

<!--導覽列-->
<header>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
   <div class="container-fluid">
      <a class="navbar-brand" href="casearch.php">
        <img src="https://lh3.googleusercontent.com/liJ9HfOMOtuQvw57oTjLy29pJuXMAcf7lE-rLYc4vB4kQZ0kuIZwNUfdwrY-hhSZQOOIifNTdCsW52TsosQVUA1c_2TG3tf4iCB4dgDLkb__iJ7L3pZtcp2bFdQ39t05-bw5pnQ9=w400" alt="" height="30" class="d-inline-block align-text-top">
        產銷履歷-產品批號查詢
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
                  <?php if (isset($_SESSION['cdep_login'])){?>
                    <li><a class="dropdown-item" href="cdepman.php">檢驗機構管理</a></li>
                  <?php }elseif (isset($_SESSION['provider_login'])){ ?>
                    <li><a class="dropdown-item" href="providerman.php">檢驗機構管理</a></li>
                  <?php }else{?>
                  <li><a class="dropdown-item" href="providersign.php">供應商</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="cdepsign.php">檢驗機構</a></li>
                <?php } ?>
            </ul>
         </li>
         </ul>
         <?php if (isset($_SESSION['provider_login']) or isset($_SESSION['cdep_login'])){?>
          <span class="navbar-text">
            提醒您，您尚未登出系統
          </span>
          <a class="nav-link active" aria-current="page" href="logout.php"><button type="button" class="btn btn-outline-warning">登出</button></a>
         <?php }?>
      </div>
   </div>
   </nav>
</header>
<main>
<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" style="height:400px; ">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner" style="height:400px;">
    <div class="carousel-item active"style="height:400px;">
      <img src="https://burst.shopifycdn.com/photos/farmer-leaning-and-tending-to-crops.jpg?width=925&format=pjpg&exif=1&iptc=1" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>小農自產代銷</h5>
        <p>讓您溯源到您手上蔬果的來歷！</p>
      </div>
    </div>
    <div class="carousel-item" style="height:400px;">
      <img src="https://cdn.pixabay.com/photo/2018/04/19/08/52/city-trans-3332623_960_720.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>生鮮冷鏈配送</h5>
        <p>專車專送，5小時內產地直達驗證單位。</p>
      </div>
    </div>
    <div class="carousel-item" style="height:400px;">
      <img src="https://cdn.pixabay.com/photo/2017/10/04/09/56/laboratory-2815641_960_720.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>專業認證有保障</h5>
        <p>專業實驗室認證，保證每批生鮮食品都符合標準及其登入內容。</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<?php

	//unset($_SESSION["PID"]);
	 // 啟用交談期
	if (isset($_POST["Search"])) {
	   // 建立SQL字串

    $sql = "SELECT P.PID, P.PNAME, P.DEADLINE, P.WEIGHT, P.COUNT, L.AREA, L.REGION, PR.MDATE, C.CDATE, CA.CNAME, FR.NAME, FR.PERSON, CA.IMAGE, CD.DNAME
            FROM PRODUCT P
            LEFT JOIN PRODUCE PR ON (P.PID = PR.PID)
            LEFT JOIN LOCATION L ON (P.LID = L.LID)
            LEFT JOIN CERTIFICATE C ON (P.PID = C.PID)
            LEFT JOIN CATEGORY CA ON (P.CID = CA.CID)
            LEFT JOIN PROVIDER FR ON (FR.FID = PR.FID)
            LEFT JOIN CDEPARTMENT CD ON (CD.DID = C.DID)";
	   $no = 0;
	   // 檢查是否輸入產品編號
	   if (chop($_POST["PID"]) != "" )
		  $PID = $_POST["PID"];
     else{
      $no = $no+1;}

      $sql.= "WHERE P.PID = '".$PID."'";
      $sql.= "AND C.CDATE != '0' AND C.CDATE IS NOT NULL";
				
	   $_SESSION["searchSQL"] = $sql;
	   $_SESSION["PID"] = $_POST["PID"];
	   $_SESSION["no"] = $no;

     $url = "casearch.php"; // 轉址
     echo "<script type='text/javascript'>";
     echo "window.location.href='$url'";
     echo "</script>";
	}

  // 取得URL參數的批號
  if (isset($_GET["PID"])){
    $sql = "SELECT P.PID, P.PNAME, P.DEADLINE, P.WEIGHT, P.COUNT, L.AREA, L.REGION, PR.MDATE, C.CDATE, CA.CNAME, FR.NAME, FR.PERSON, CA.IMAGE, CD.DNAME
            FROM PRODUCT P
            LEFT JOIN PRODUCE PR ON (P.PID = PR.PID)
            LEFT JOIN LOCATION L ON (P.LID = L.LID)
            LEFT JOIN CERTIFICATE C ON (P.PID = C.PID)
            LEFT JOIN CATEGORY CA ON (P.CID = CA.CID)
            LEFT JOIN PROVIDER FR ON (FR.FID = PR.FID)
            LEFT JOIN CDEPARTMENT CD ON (CD.DID = C.DID)";
    $PID = $_GET["PID"];
    $sql.= "WHERE P.PID = '".$PID."'";
    $sql.= "AND C.CDATE != '0' AND C.CDATE IS NOT NULL";
    $_SESSION["searchSQL"] = $sql;
    //echo "SQL GET：".$_SESSION["searchSQL"];
    $_SESSION["PID"] = $PID;
    $no = 0;
    $_SESSION["no"] = $no;
    //echo "NO：".$no;
  }
?>

<div class="container">
<?php if(isset($_SESSION["PID"])) {
  $PPID = $_SESSION["PID"];
}else{
  $PPID = "";
  $_SESSION["no"] = "";
  //$_SESSION["searchSQL"] = "";
}
   ?>
<form action="casearch.php" method="post">
  <div class="form-group row">
    <div class="col-md-12">
		<div class="row">
			<div class="col-md-12"><p style="margin:10px 0px 5px 0px;">批號查詢</p></div>
			<div class="col-md-12">
				<input class="form-control" type="search" name="PID" id="search" placeholder="請輸入產品批號" value="<?php echo $PPID ?>">
			</div>
		</div>
	</div>
</div>
<center>
	  <button class="btn" style="background-color: #3db3c5;color: white;margin-top: 35px;" type="submit" name="Search" id="Search" >搜尋</button></form>
</center>

<!-- Three columns of text below the carousel -->
<div class="row">
<?php
//echo "NO：".$_SESSION["no"];
if ($_SESSION["no"]>0){?>
<center>
  <h1 style="margin-top: 100px; font-weight:700; color:red;">您尚未填入查詢字串<br></h1>
  <h4 style="margin-bottom: 80px;">請您掃描農產品上方農產履歷QR Code或手動輸入產品批號，謝謝！</h4>
</center>
<?php }elseif(isset($_SESSION["logoutref"])){ 
  unset($_SESSION["logoutref"]);
}elseif(empty($_SESSION["searchSQL"])){
}else{
  $sql = $_SESSION["searchSQL"];
  //echo "SQL：".$sql;
  $conn = oci_connect("Group9", "groupp9", "140.117.69.58/orcl.cm.nsysu.edu.tw",'AL32UTF8');
  $result = oci_parse($conn, $sql);
  oci_execute($result);
  ?>
    <?php
    $j=0;
    while (($row = oci_fetch_array($result, OCI_ASSOC)) != false){
    ?>

    <h1 style="text-align:center; margin-top: 30px;margin-bottom: 20px; font-weight:700">查詢結果</h1>
    <div class="row mb-2">
        <div class="col-md-12">
          <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col p-4 d-flex flex-column position-static">
              <strong class="d-inline-block mb-2 text-primary"><?php echo "#". $row['PID'] ?></strong>
              <h3 class="mb-0"><?php echo $row['PNAME'] ?></h3>
              <div class="mb-1 text-muted">製造日期：<?php echo $row['MDATE'] ?><br>有效期限：<?php echo $row['DEADLINE'] ?>天</div>
              <p class="card-text mb-auto"><?php echo "供應商：".$row['NAME']?><?php echo "<br>負責人：".$row['PERSON'] ?></p>
            </div>
            <div class="col p-4 d-flex flex-column position-static">
              <strong class="d-inline-block mb-2 text-primary">　</strong>
              <h3 class="mb-0">　</h3>
              <p class="card-text mb-auto"><?php echo "單品重量：".$row['WEIGHT']?>公克<?php echo "<br>產地：".$row['AREA']." ".$row['REGION'] ?><br>分類：<?php echo $row['CNAME'] ?><br>檢驗機構：<?php echo $row['DNAME'] ?></p>
            </div>
            <div class="col-auto d-none d-lg-block">
              <img src="<?php echo $row['IMAGE'] ?>" style="height:200px;margin-right: 25px;" alt="<?php echo $row['CNAME'] ?>">
            </div>
          </div>
        </div>
      </div>
      <?php 
      $j++;
    }?>
    <script><?php
    if ((($row = oci_fetch_array($result, OCI_ASSOC)) != true) and $j<1){ ?>
      swal("查無資料！", "您查詢的批號可能還尚未完成檢驗，或是尚無該批號。","warning")
    <?php } ?>
    </script>
    </tbody>
  </table>
<?php } ?>

</div><!-- /.row -->

<!-- /END THE FEATURETTES -->

</div><!-- /.container -->


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