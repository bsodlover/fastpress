<link rel="stylesheet" href="assets/bootstrap.css">

<title>delete posts</title>
<!-- Search form -->
<h1>Delete posts</h1>
<form method="GET" action="">
<input name="search" class="form-control" type="text" placeholder="Search" aria-label="Search">
</form>
<div class="row">

<?php

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: admin/login.php?e=403");
    exit;
}

require_once 'admin/config.php';

if (isset($_GET["search"])) {

}

if (isset($_GET["search"])) {
	$query = $_GET["search"];
$sql = "SELECT * FROM `fp` WHERE (`pImgUrl` LIKE '%$query%' OR `pKeywords` LIKE '%$query%' OR `pUrl` LIKE '%$query%' OR `pTitle` LIKE '%$query%' OR `pSubtitle` LIKE '%$query%' OR `pDate` LIKE '%$query%' OR `pCategory` LIKE '%$query%') LIMIT 50";
} else {$sql = "SELECT * FROM `fp` ORDER BY `pDate` DESC LIMIT 100";}
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
    if (empty($row["pImgUrl"])) {
       $pImgUrl = "noimg.png";
     } else {$pImgUrl = $row["pImgUrl"];}
        echo '<div class="col-md-2 col-md-offset-5"><div class="card" style="width: 18rem;">
  <img class="card-img-top" src="p/img/'. $pImgUrl .'" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title">' . $row["pTitle"] . '</h5>
    <p class="card-text">'. $row["pSubtitle"] .'</p>
    <a href="?deleteid=' . $row["pId"] . '" class="btn btn-danger">Delete post</a>
  </div>
</div></div>';
    }
} else {
    echo "<h1>POST NOT FOUND</h1>";
}


if (isset($_GET["deleteid"])) {
$deleteid = $_GET["deleteid"];
require_once 'admin/config.php';
$sql = "SELECT * FROM `fp` WHERE (`pId` = $deleteid)";
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
// output data of each row
while($row = mysqli_fetch_assoc($result)) {
$pUrl =  $row["pUrl"];
$pImgUrl = $row["pImgUrl"];
}
} else {
$error++;
}




require_once 'admin/config.php';
$sql = "DELETE FROM `fp`
WHERE ((`pId` = $deleteid))";
if (mysqli_query($link, $sql)) {
echo "Record deleted successfully";
} else {
$error++;
}
$myFile = "$pUrl";
unlink($myFile) or $error++;
$myImg = "p/img/$pImgUrl";
unlink($myImg) or $error = $error;
mysqli_close($link);
if(empty($error)){$errormsg = "File deleted successfully";} else {$errormsg = "File could not be deleted, maybe it has already been deleted.";}
echo '<script>alert("'. $errormsg .'"); window.location = "post_manage.php";</script>';
}
?>
</div>