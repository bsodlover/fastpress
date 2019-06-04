<!doctype html>
<html lang="en">    
  <head>
  <style>
  /* SET THE COLORS OF THE WARNING MESSAGES */
  warning { background-color: #ffae42; /* Red */ width: 80%; color: white; font-size: 20px;}
  success {background-color: #3CB371; width: 80%; color: white; font-size: 20px;}
  danger {background-color: red; width: 80%; color: white; font-size: 20px;}

  </style>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/bootstrap.css">




    <title>Upload</title>
  </head>
  <body>

    <?php require_once 'header.php'; ?>
          <main role="main" class="container">
          <?php
require_once 'admin/config.php';
if(isset($_GET["category"])) {
$catId = $_GET["category"];
$searchVal = array("/", '"', "'","-",';','=');  //values to be replaced
//<Remove characters to protect from SQL injections>
$name = str_replace($searchVal, "", htmlentities($_POST["name"]));
$bio = str_replace($searchVal, "", htmlentities($_POST["bio"])); 
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT  *  FROM cat where catId = $catId ";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
	$catName = $row["catName"];
	echo '<h1>' . $catName  . '</h1>' . '<h2>' . $row["catDescription"] . '</h2>';
	   
  }
} else {
die("Category not found.");
}

$sql = "SELECT * FROM `fp` WHERE `pCategory` = '$catName' ORDER BY `pId` DESC LIMIT 50 ";
echo $sql;
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "id: " . $row["pTitle"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
} else {
    echo "0 results";
}

mysqli_close($conn);	
} else {
    $sql = "SELECT * FROM fp";
echo $sql;
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "id: " . $row["pTitle"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
} else {
    echo "0 results";
}   
}
?>
      
          </main><!-- /.container -->
      
  <script src="assets/jquery.js"></script>
  <script src="assets/popper.js"></script>
  <script src="assets/bootstrap.js"></script>
  </body>
</html>
