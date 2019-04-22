<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.4.0.min.js" integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg=" crossorigin="anonymous"></script>
  <title>Image Gallery</title>
  <style>
    .card-img-top {
      height: 200px;
    }
    #closeAlert:hover {
      cursor: pointer;
    }
  </style>
</head> 
<body>

<?php 
// Define these errors in an array
$upload_errors = array(
  UPLOAD_ERR_OK 			  	=> "No errors.",
  UPLOAD_ERR_INI_SIZE  		=> "Larger than upload_max_filesize.",
  UPLOAD_ERR_FORM_SIZE 		=> "Larger than form MAX_FILE_SIZE.",
  UPLOAD_ERR_PARTIAL 			=> "Partial upload.",
  UPLOAD_ERR_NO_FILE 			=> "No file.",
  UPLOAD_ERR_NO_TMP_DIR 	=> "No temporary directory.",
  UPLOAD_ERR_CANT_WRITE		=> "Can't write to disk.",
  UPLOAD_ERR_EXTENSION 		=> "File upload stopped by extension.");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
   if (isset($_GET['del'])) {
     unlink("images/".$_GET['del']);
     header("Location: index.php");
   }
}

if($_SERVER['REQUEST_METHOD'] == "POST"){  
  $error = $_FILES['file_upload']['error'];
  if ($error == 0) {
    // what file do we need to move?
    $tmp_file = $_FILES['file_upload']['tmp_name'];

    // set target file name
    // basename gets just the file name
    $target_file = basename($_FILES['file_upload']['name']);

    // set upload folder name
    $upload_dir = 'images';

    // Now lets move the file
    // move_uploaded_file returns false if something went wrong
    move_uploaded_file($tmp_file, $upload_dir . "/" . $target_file);
    $message = "File uploaded successfully";  
  } else {
    $error = $_FILES['file_upload']['error'];
    $message = $upload_errors[$error];
  }
} 
?>
  <h1 class="text-center p-3">Images Gallery</h1>
<div class="container">
  <div class="row">
    <div class="col-lg-12 bg-light rounded mb-5">
    <?php if(!empty($message)) {
    echo '<div class="alert alert-success mt-3 d-flex" role="alert" id="uploadAlert">';
    echo "<p class='m-0'>{$message}</p>";
    echo '<i class="fas fa-times ml-auto align-self-center" id="closeAlert"></i>';    
    echo '</div>';
    } ?>
      <form action="" class="my-3 alert alert-dark p-3" method="post" enctype="multipart/form-data">
        <div class="custom-file w-auto">
          <input type="hidden" name="MAX_FILE_SIZE" value="15000000">
          <input type="file" class="custom-file-input" id="customFile" name="file_upload" required>
          <label class="custom-file-label" for="customFile">Choose file</label>
        </div>
        
        <button class="btn btn-primary d-inline-block mt-1" type="submit">Upload Image</button>        
      </form>


      <div class="d-flex flex-wrap justify-content-center">      

        <?php
        $dir = "images";        
        if((is_dir($dir))){
          $dir_array = scandir($dir);
          foreach ($dir_array as $file) {
            // don't display the . and .. directories. Using the strpos() for this.
            if(strpos($file,'.') > 0){
              $fileName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
              echo "<div class='card mb-3 mx-2' style='width: 18rem;'>";
                echo "<img src='images/$file' class='card-img-top' alt='...'>";
                echo "<div class='card-body'>";
                  echo "<h5 class='card-title text-center mt-auto'>$fileName</h5>";
                  echo "<a href='index.php?del=$file' class='btn btn-outline-secondary d-block mx-auto'>Delete</a>";
                echo "</div>";
              echo "</div>";              
            }
          }          
        } 
        if (count(glob("$dir/*")) == 0) {
          echo "<div class='alert alert-secondary w-100 m-4 text-center' role='alert'>Gallery is empty!</div>";
        }
         // end of if
        ?>
      </div>

    </div><!-- col-lg-12 -->
  </div><!-- row -->
</div><!-- container -->
<script>
$('.custom-file-input').on('change', function() { 
   let fileName = $(this).val().split('\\').pop(); 
   $(this).next('.custom-file-label').addClass("selected").html(fileName); 
});

$("#closeAlert").click(function(){
  $(this).parent().remove();
});
setInterval(function(){
  $("#uploadAlert").fadeOut(1000,function() { 
      $(this).remove();
    });
}, 7000);
</script>
</body>
</html>