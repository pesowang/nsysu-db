<!doctype html>
<html lang="en">
  <head>
  <?php session_start();  // 啟動交談期 ?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <title>供應商 | 編輯農產品</title>
  </head>
  <body>
<!--導覽列-->
<header>
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
<main style="margin-top:90px;">
<?php
  if (isset($_SESSION['provider_login']))
  {
    date_default_timezone_set('Asia/Taipei');
    $getDate= date("Y-m-d");
  ?>

<?php
$id = $_GET["id"];  // 取得URL參數的編號
$action = $_GET["action"];  // 取得操作種類
$conn = oci_connect("Group9", "groupp9", "140.117.69.58/orcl.cm.nsysu.edu.tw",'AL32UTF8');

// 執行操作
switch ($action) {
   case "update": // 更新操作    
      $pname = $_POST["pname"]; 
      $deadline = $_POST["deadline"];
      $weight = $_POST["weight"];
      $mdate = $_POST["mdate"];
      $location = $_POST["location"];
      $category = $_POST["category"];
      $fid = $_POST["fid"];
      $count = $_POST["count"];

      $sql = "UPDATE PRODUCT SET PNAME='".$pname."', DEADLINE='".$deadline."', WEIGHT='".$weight."', LID='".$location."',  CID='".$category."', COUNT='".$count."' WHERE PID=".$id."";
      $stmt = oci_parse($conn, $sql);
      oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
      oci_free_statement($stmt);

      $sql = "UPDATE PRODUCE SET FID='".$fid."', MDATE='".$mdate."' WHERE PID=".$id."";
      $stmt = oci_parse($conn, $sql);
      oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
      oci_free_statement($stmt);

	    echo "<script>alert('更新資料完成：按下確認後將跳轉至資料瀏覽！'); location.href = 'providerman.php'; </script>";
      break;
   case "del":    // 刪除操作

      $query = oci_parse($conn, "DELETE FROM CERTIFICATE WHERE PID='".$id."'");
      $result = oci_execute($query, OCI_DEFAULT);

      $query = oci_parse($conn, "DELETE FROM PRODUCE WHERE PID='".$id."'");
      $result = oci_execute($query, OCI_DEFAULT);   

      $query = oci_parse($conn, "DELETE FROM PRODUCT WHERE PID='".$id."'");
      $result = oci_execute($query, OCI_DEFAULT); 
      if($result)  
      {  
        oci_commit($conn); //*** Commit Transaction ***// 
        $_SESSION['del']=TRUE;
        header("Location: providerman.php"); // 轉址
        //echo "Data Deleted Successfully.";
      }
      else{
        echo "Error2.";
      } 
      break;
   case "edit":   // 編輯操作
    $query = "SELECT P.PID, P.PNAME, P.DEADLINE, P.WEIGHT, P.LID, PR.MDATE, P.COUNT, P.CID, PF.PERSON, PF.FID
              FROM PRODUCT P
              LEFT JOIN PRODUCE PR ON (P.PID = PR.PID)
              LEFT JOIN PROVIDER PF ON (PR.FID = PF.FID)
              WHERE P.PID = '".$id."'";
    //echo "QUERY：".$query;
    $result = oci_parse($conn, $query);
    oci_execute($result);
    while (($row = oci_fetch_array($result, OCI_ASSOC)) != false){ //取得登入者名稱
      $pname = $row["PNAME"]; 
      $deadline = $row["DEADLINE"];
      $weight = $row["WEIGHT"];
      $mdate = $row["MDATE"];
      $location = $row["LID"];
      $category = $row["CID"];
      $fid = $row["FID"];
      $count = $row["COUNT"];
    }
  }
// 顯示編輯表單
?>

  <div class="container">
      <form name="login" method="post" action="provideredit.php?action=update&id=<?php echo $id ?>" method="post">
      <div class="row justify-content-start">
      <div class="col-md-5">
        <label for="exampleFormControlInput1" class="form-label">品名</label>
        <input type="text" class="form-control" name="pname" placeholder="請輸入品名" value="<?php echo $pname ?>" required="required">
      </div>
      <div class="col-md-2">
        <label for="exampleFormControlTextarea1" class="form-label">保存期限</label>
        <input type="number" class="form-control" name="deadline" min="3" placeholder="最小保存天數為3天" value="<?php echo $deadline ?>" required="required">
      </div>
      <div class="col-md-3">
        <label for="exampleFormControlTextarea1" class="form-label">產品重量</label>
        <input type="number" class="form-control" name="weight" min="250" placeholder="單一產品重量需大於250g" value="<?php echo $weight ?>" required="required">
      </div>
      <div class="col-md-2">
        <label for="exampleFormControlTextarea1" class="form-label">數量</label>
        <input type="number" class="form-control" name="count" min="10" placeholder="分裝籃數/袋數" value="<?php echo $count ?>" required="required">
      </div>
      </div>
      <div class="row justify-content-start" style="margin-top:20px;">
      <div class="col-md-4">
        <label for="exampleFormControlInput1" class="form-label">生產日期</label>
        <input type="date" class="form-control" name="mdate" value="<?php echo $mdate ?>" max="<?php echo $getDate ?>" required="required">
      </div>
      <div class="col-md-4">
        <label for="exampleFormControlTextarea1" class="form-label">產地名稱及區域</label>
        <select class="form-select form-select mb-3" aria-label=".form-select-sm example" name="location" required>
          <option value="1" <?php if ($location=="1"){echo "selected";} ?>>雲林西螺</option>
          <option value="2" <?php if ($location=="2"){echo "selected";} ?>>嘉義梅山</option>
          <option value="3" <?php if ($location=="3"){echo "selected";} ?>>台南鹽水</option>
          <option value="4" <?php if ($location=="4"){echo "selected";} ?>>台南麻豆</option>
          <option value="5" <?php if ($location=="5"){echo "selected";} ?>>高雄燕巢</option>
          <option value="6" <?php if ($location=="6"){echo "selected";} ?>>高雄大社</option>
          <option value="7" <?php if ($location=="7"){echo "selected";} ?>>高雄旗山</option>
          <option value="8" <?php if ($location=="8"){echo "selected";} ?>>屏東萬丹</option>
          <option value="9" <?php if ($location=="9"){echo "selected";} ?>>台東關山</option>
          <option value="10"<?php if ($location=="10"){echo "selected";} ?>>台東池上</option>
          <option value="11"<?php if ($location=="11"){echo "selected";} ?>>花蓮富里</option>
          <option value="12"<?php if ($location=="12"){echo "selected";} ?>>宜蘭五結鄉</option>
          <option value="13"<?php if ($location=="13"){echo "selected";} ?>>苗栗大湖</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="exampleFormControlTextarea1" class="form-label">種類名稱</label>
        <select class="form-select form-select mb-3" aria-label=".form-select-sm example" name="category" required>
          <option value="1" <?php if ($category=="1"){echo "selected";} ?>>蘋果</option>
          <option value="2" <?php if ($category=="2"){echo "selected";} ?>>香蕉</option>
          <option value="3" <?php if ($category=="3"){echo "selected";} ?>>西瓜</option>
          <option value="4" <?php if ($category=="4"){echo "selected";} ?>>芒果</option>
          <option value="5" <?php if ($category=="5"){echo "selected";} ?>>根莖類</option>
          <option value="6" <?php if ($category=="6"){echo "selected";} ?>>葉菜類</option>
        </select>
      </div>
      </div>
      <div class="row justify-content-start">
      <div class="col-md-4">
        <label for="exampleFormControlInput1" class="form-label">供應商編號(系統自動填入)</label>
        <input type="text" class="form-control" name="fid" value="<?php echo $_SESSION['provider_login'] ?>" readonly>
      </div>
      <div class="col-md-4">
        <label for="exampleFormControlTextarea1" class="form-label">負責人(系統自動填入)</label>
        <input type="text" class="form-control" name="person" value="<?php echo $_SESSION['provider_person'] ?>" readonly>
      </div>
      <div class="col-md-4">
        <label for="exampleFormControlTextarea1" class="form-label">農場名稱(系統自動填入)</label>
        <input type="text" class="form-control" name="name" value="<?php echo $_SESSION["provider_name"] ?>" readonly>
      </div>
      </div>
      <center>
      <button type="submit" class="btn btn-outline-success" style="margin-top:30px;">儲存</button>
      </center>
    </form>
</div><!--row-->

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
    <footer class="container fixed-bottom">
    <hr class="featurette-divider">
    <p class="float-end"><a href="#"><img src="https://img.icons8.com/flat-round/344/collapse-arrow--v1.png" style="height:20px;"></a></p>
    <p>&copy; 國立中山大學 NSYSU, 資管所 DBProject_Group 9</p>
  </footer>
</main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  </body>
</html>
