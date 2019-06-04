<?php 
//TODO: HEM DE FER QUE LA CATEGORY ES MOSTRI EN EL HTML CREAT, FER UN CATEGORIES PHP PUBLIC Y Q ES LINKEJI ENTRE SI.
//FIXME: FER ALGUNES DE AQUESTES PETICIONS SQL AMB FUNCTIONS
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: admin/login.php?e=403");
    exit;
}
$f_username = $_SESSION["username"];
require_once 'admin/config.php';
?>
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

<script src='https://cloud.tinymce.com/5/tinymce.min.js?apiKey=<?php echo $tinymcekey; // ENTER THE API KEY OF TINYMCE SET ON THE CONFIG.PHP ?>'></script> 
  <script>
  tinymce.init({
    selector: '#mytextarea' 
  }); //Just add tinymce to the text part, to avoid issues with the other parts.
  </script>


    <title>Upload</title>
  </head>
  <body>

    <header>
      <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand">Upload</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="../">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin/category_manage.php">Manage Categories</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="post_manage.php">Manage Posts</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
  <main class="container">
  <div id="alert"></div>
  <div class="card mb-4 box-shadow">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">Post</h4>
          </div>
          <div class="card-body">
            <form class="form-group" method="POST" action="" enctype = "multipart/form-data">  
     <div class="form-group">
      <label for="exampleFormControlTextarea1">Title</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="title" placeholder="MAXIMUM 30 WORDS" required></textarea>
    <label for="exampleFormControlTextarea1">Subtitle</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="subtitle" required></textarea>
   
     <label for="exampleFormControlTextarea1">KeyWords</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="keywords" required></textarea>
<label for="exampleFormControlTextarea1">Category</label>
    
<select name="category">
<?php 
require_once 'admin/config.php';
$sql = "SELECT * FROM `cat`";
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . $row["catId"] . '">' . $row["catName"] . '</option>';
    }
} else {
    echo "0 results";
}

?>
  </select> <br>


    <label for="mytextarea">Text</label>
    <textarea class="form-control" id="mytextarea" rows="3" name="text" placeholder="TEXT"></textarea>
<br>
          </div>
        </div>
        <div class="card-body">
            <h1 class="card-title pricing-card-title">Image<small class="text-muted"></small></h1>
            <input type = "file" name = "image" /><br>
         <input type = "text" name="pImgAlt" placeholder="Little description of the image, (alt attribute)"/><br>           
         <br><button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>

 
           


  </div>
   </form>

  </main><!-- Container -->

      <!-- FOOTER -->


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="assets/jquery.js"></script>
  <script src="assets/popper.js"></script>
  <script src="assets/bootstrap.js"></script>
  </body>

<?php
$pCategoryId = $_POST["category"];
if (isset($_POST["title"])) {
//GATHER DATA FROM THE FORM & NAMING VARIABLES
require_once 'admin/config.php';

$sql = "SELECT * FROM `cat` WHERE `catId` = '$pCategoryId' LIMIT 1";
echo $sql;
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $pCategoryName = $row["catName"];
    }
} else {
    echo "0 results";
}

$h1 = '<strong><i><h1 class="display-3">'. $_POST["title"] . '</h1></i></strong>';
$pTitle = $_POST["title"];
$pSubtitle = $_POST["subtitle"];
$pKeywords = $_POST["keywords"];
$filenam = urlencode($_POST["title"]);
$filename = "$filenam.php";
$pUrl = "p/$filename";
$h2 = '<strong><h2 class="lead">'. $_POST["subtitle"] . '</h2></strong>';
$p = $_POST["text"];
$page = $h1 . $h2 . $p;
$date = date("Y-m-d:h:i:sa");
$pImgAlt = $_POST["pImgAlt"];
//OPTIONAL, UPLOADING IMAGES
 if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      $expensions= array("jpeg","jpg","zip","rar","png","exe","docx","odt","rtf","wav","mp4","gif");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="Solo puedes subir archvios .zip, .rar, o .jpeg";
      }
      
      if($file_size > 20971520000000) {
         $errors[]='Archivo demasiado grande';
      }
      
      if(empty($errors)==true) {
         move_uploaded_file($file_tmp,"p/img/".$file_name);
         
      }else{
         $imgerror = 1;
      }
   }
$imagename = rawurlencode($file_name);
//creating the html file
$htmlpage = '
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="' . $_POST["subtitle"] . '">
    <meta name="author" content="' . $f_username . '">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
    
    <link rel="stylesheet" href="../assets/bootstrap.css">

    <title>' . $_POST["title"] . '</title>

  </head>

  <body> ' . "
<?php require_once('../header.php'); ?> " . '

<main class="container lead my-3"><div class="jumbotron">' . $h1 . $h2 . '<p><i>Date:' . $date . '</i><strong> Category: <a href="../category.php?category=' . $pCategoryId . '">' . $pCategoryName . '</a></strong></p><img class="img-fluid"  style="width: 100%; display: block;"  src="img/' . $imagename . '"></img></div>' . $p . '</main> ' . "
<?php require_once('../similar.php'); ?>
<?php require_once('../footer.php'); ?> " . '
  <script src="../assets/jquery.js"></script>
  <script src="../assets/popper.js"></script>
  <script src="../assets/bootstrap.js"></script>
  </body>

</html>

';

//SEND VALUES TO DB


$myfile = fopen("p/$filename", "w") or die('<script> alert("The fastpress/p folder does not have permissions to be writable by your web server. Read the install instructions");</script>');
$txt = $htmlpage;
fwrite($myfile, $txt);
fclose($myfile);

$sql = "INSERT INTO `fp` (`pKeywords`, `pUrl`, `pTitle`, `pSubtitle`, `pDate`, `pCategory`, `pImgUrl`, `pImgAlt`)
VALUES ('$pKeywords', '$pUrl', '$pTitle', '$pSubtitle', now(), '$pCategory', '$imagename', '$pImgAlt');";

if ($link->query($sql) === TRUE) {
    $dberror = 0;
} else {
    echo "Error: " . $sql . "<br>" . $link->error;
$dberror = 1;
}

$link->close();

} else {die();}
 ?>
 <script>
 // DEPENDING IF THERE HAS BEEN ERRORS OR NOT, WE WILL DISPLAY AN ERROR MESSAGE, ON THE DIV "ALERT"
 document.getElementById("alert").innerHTML = "<?php
 if (isset($_POST["title"])) {if ($dberror == 0 && $imgerror == 0){
echo '<success>Post created  succesfully</success>';
} else{ if($imgerror == 1 && $dberror == 0){
  echo '<warning>Post created  succesfully BUT IMAGE WASNT UPLOADED. Please try a diferent image.</warning> ';} else {echo '<danger>Post was not created due to database errors.<danger>';} } }
?>";</script> 
 

</html>
