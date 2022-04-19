<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="themesm/man.css" />
	<link rel="stylesheet" href="themesm/jquery.mobile.icons.min.css" />
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile.structure-1.4.5.min.css" />
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	<title>系統登出</title>
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans+TC&display=swap" rel="stylesheet">
    <style>
      body {
        font-family: 'Noto Sans TC', sans-serif;
      }
    </style>
</head>

<body>
	<?php
session_start();
session_unset();
session_destroy();
session_start();
$_SESSION["logout"] = TRUE;
$_SESSION["logoutref"] = TRUE;

$url = "casearch.php";
echo "<script type='text/javascript'>";
echo "window.location.href='$url'";
echo "</script>"; 

?>
</body>
</html>