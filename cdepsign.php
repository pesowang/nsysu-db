<!doctype html>
<html lang="en">
  <head>
  <?php session_start();  // 啟動交談期?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <title>檢驗機構 | 登入頁</title>
<style>
  html,
  body {
    height: 100%;
  }

  body {
    display: flex;
    align-items: center;
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #f5f5f5;
  }

  .form-signin {
    width: 100%;
    max-width: 330px;
    padding: 15px;
    margin: auto;
  }

  .form-signin .checkbox {
    font-weight: 400;
  }

  .form-signin .form-floating:focus-within {
    z-index: 2;
  }

  .form-signin input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }

  .form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }

</style>


  </head>
  <body>
<!--導覽列-->
<header>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
   <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="https://lh3.googleusercontent.com/liJ9HfOMOtuQvw57oTjLy29pJuXMAcf7lE-rLYc4vB4kQZ0kuIZwNUfdwrY-hhSZQOOIifNTdCsW52TsosQVUA1c_2TG3tf4iCB4dgDLkb__iJ7L3pZtcp2bFdQ39t05-bw5pnQ9=w400" alt="" height="30" class="d-inline-block align-text-top">
        產銷履歷-檢驗機構維護系統
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
               <li><a class="dropdown-item" href="providersign.php">供應商</a></li>
               <li><hr class="dropdown-divider"></li>
               <li><a class="dropdown-item" href="cdepsign.php">檢驗機構</a></li>
            </ul>
         </li>
         </ul>
      </div>
   </div>
   </nav>
</header>
<body class="text-center">
    
    <main class="form-signin">
      <form action="cdepsign.php" method="post">
        <img class="mb-4" src="https://img.icons8.com/stickers/344/microscope.png" alt="" width="150">
        <h1 class="h3 mb-3 fw-normal">檢驗機構登入頁</h1>
      <?php
      $username = "";  $password = "";
      // 取得表單欄位值
      if ( isset($_POST["Username"]) )
        $username = $_POST["Username"];
      if ( isset($_POST["Password"]) )
        $password = $_POST["Password"];
      // 檢查是否輸入使用者名稱和密碼
      if ($username != "" && $password != "") {
        // 建立資料庫連接 
        $conn = oci_connect("Group9", "groupp9", "140.117.69.58/orcl.cm.nsysu.edu.tw",'AL32UTF8')or die("無法開啟資料庫連接!<br/>");
        $query = "SELECT * FROM CDEPARTMENT WHERE PASS='".$password."' AND DID='".$username."'";
        //echo $query;
        $result = oci_parse($conn, $query);
        oci_execute($result);

        $total_records = oci_fetch($result);
        
        // 是否有查詢到使用者記錄
        if ( $total_records > 0 ) {
            // 成功登入, 指定Session變數
            $_SESSION["cdep_login"] = $username;
            //$_SESSION["SQL"] = $sql;
            header("Location: cdepman.php");
        } else {  // 登入失敗
            echo "<center><font color='red' size='5'>";
            echo "使用者名稱或密碼錯誤!<br/>";
            echo "</font>";
            $_SESSION["cdep_login"] = false;
        }
        oci_free_statement($result);
        oci_close($conn);  // 關閉資料庫連接  
      }
      ?>    
        <div class="form-floating">
          <input type="text" class="form-control" id="floatingInput" name="Username">
          <label for="floatingInput">您的ID</label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" id="floatingPassword" name="Password">
          <label for="floatingPassword">您的密碼</label>
        </div>
    
        <div class="checkbox mb-3">
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">登入</button>
        <p class="mt-5 mb-3 text-muted">&copy; 國立中山大學 NSYSU, 資管所 DBProject_Group 9</p>
      </form>
    </main>
    
    
        
      </body>
</html>