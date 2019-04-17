<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.4.0.min.js" integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg=" crossorigin="anonymous"></script>
  <title>Image Gallery</title>
  <style>
    .card-img-top {
      height: 200px;
    }
  </style>
</head> 
<body>
  <h1 class="text-center p-3">Images Gallery</h1>
<div class="container">
  <div class="row">
    <div class="col-lg-12 bg-light ">
      <form action="" class="mx-4 mt-4 mb-0 alert alert-dark p-3" method="post" enctype="multipart/form-data">
        <div class="custom-file w-auto">
          <input type="hidden" name="MAX_FILE_SIZE" value="100000000">
          <input type="file" class="custom-file-input" id="customFile" name="file_upload" required>
          <label class="custom-file-label" for="customFile">Choose file</label>
        </div>
        
        <button class="btn btn-primary d-inline-block mt-1" type="submit">Upload Image</button>
      </form>
    
      <div class="d-flex flex-wrap justify-content-center">      

        <?php 
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
           if (isset($_GET['del'])) {
             unlink("images/".$_GET['del']);
             header("Location: index.php");
           }
        }
        if($_SERVER['REQUEST_METHOD'] == "POST"){

          // what file do we need to move?
          $tmp_file = $_FILES['file_upload']['tmp_name'];

          // set target file name
          // basename gets just the file name
          $target_file = basename($_FILES['file_upload']['name']);

          // set upload folder name
          $upload_dir = 'images';

          // Now lets move the file
          // move_uploaded_file returns false if something went wrong
          if(move_uploaded_file($tmp_file, $upload_dir . "/" . $target_file)){
            $message = "File uploaded successfully";
          } else {
            $error = $_FILES['file_upload']['error'];
            $message = $upload_errors[$error];
          } // end of if
        } // end of if

        $dir = "images";        
        if((is_dir($dir))){
          $dir_array = scandir($dir);
          foreach ($dir_array as $file) {
            // don't display the . and .. directories. Using the strpos() for this.
            if(strpos($file,'.') > 0){
              $fileName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
              echo "<div class='card m-3' style='width: 18rem;'>";
                echo "<img src='images/$file' class='card-img-top' alt='...'>";
                echo "<div class='card-body'>";
                  echo "<h5 class='card-title text-center'>$fileName</h5>";
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
</script>
</body>
</html>