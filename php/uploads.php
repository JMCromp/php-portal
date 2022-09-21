<?php
session_start();

error_reporting('~E_NOTICE');

$message = ''; 
if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload')
{
  if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK)
  {
    // get details of the uploaded file
    $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
    $fileName = $_FILES['uploadedFile']['name'];
    $fileSize = $_FILES['uploadedFile']['size'];
    $fileType = $_FILES['uploadedFile']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
 
    // sanitise file-name
    // $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $newFileName = $fileName;
 
    // check if file has one of the following extensions
    $allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc');
 
    if (in_array($fileExtension, $allowedfileExtensions))
    {
      // directory in which the uploaded file will be moved
      $uploadFileDir = '/var/www/html/uploaded_files/';
      $dest_path = $uploadFileDir . $newFileName;
 
      if(move_uploaded_file($fileTmpPath, $dest_path)) 
      {
        $message ='File was successfully uploaded.';
      }
      else
      {
        $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable.';
      }
    }
    else
    {
      $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
    }
  }
  else
  {
    $message = 'There was a problem uploading your file.<br>';
    $message .= 'Error: ' . $_FILES['uploadedFile']['error'];
  }
}
$_SESSION['message'] = $message;
// header("Location: uploads.php");

require_once ('authrole.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>My Account</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="../styles/style.css" rel="stylesheet" type="text/css">
        <link href="../styles/comments.css" rel="stylesheet" type="text/css">
        <link href="../styles/footer.css" rel="stylesheet" type="text/css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Portal</h1>
				<a href="account.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="uploads.php"><i class="fas fa-upload"></i>Uploads</a>
				<?php if ($role == 'admin') : ?>
                		<a href="admin.php"><i class="fas fa-cog"></i>Admin</a>
				<?php endif; ?>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Uploads</h2>
      <div class="uploads">
        <?php
          if (isset($_SESSION['message']) && $_SESSION['message']) {
            echo '<p class="notification">'.$_SESSION['message'].'</p>';
            unset($_SESSION['message']);
            }
        ?>
        <form method="POST" action="uploads.php" enctype="multipart/form-data">
          <div class="upload-wrapper">
          <p>Choose a file to upload:</p>
            <span class="file-name"></span>
            <label for="file-upload"><input type="file" id="file-upload" name="uploadedFile"></label>
          </div>
          <input type="submit" name="uploadBtn" value="Upload"/>
        </form>
        <?php
          if ($handle = opendir('/var/www/html/uploaded_files/')) {
            while (false !== ($file = readdir($handle))) {
              if ($file != "." && $file != "..") {
                  $thelist .= '<li><a href="/var/www/html/uploaded_files/'.$file.'">'.$file.'</a></li>';
                }
              }
              closedir($handle);
            }
            ?>
            <!-- <h1>List of files:</h1>
            <ul><?php echo $thelist; ?></ul> -->
      </div>

      <!-- Comments section -->
      <div class="comments"></div>
      <script>
      const comments_page_id = 1; // This number should be unique on every page
      fetch("comments.php?page_id=" + comments_page_id).then(response => response.text()).then(data => {
        document.querySelector(".comments").innerHTML = data;
        document.querySelectorAll(".comments .write_comment_btn, .comments .reply_comment_btn").forEach(element => {
          element.onclick = event => {
            event.preventDefault();
            document.querySelectorAll(".comments .write_comment").forEach(element => element.style.display = 'none');
            document.querySelector("div[data-comment-id='" + element.getAttribute("data-comment-id") + "']").style.display = 'block';
            document.querySelector("div[data-comment-id='" + element.getAttribute("data-comment-id") + "'] input[name='name']").focus();
          };
        });
        document.querySelectorAll(".comments .write_comment form").forEach(element => {
          element.onsubmit = event => {
            event.preventDefault();
            fetch("comments.php?page_id=" + comments_page_id, {
              method: 'POST',
              body: new FormData(element)
            }).then(response => response.text()).then(data => {
              element.parentElement.innerHTML = data;
            });
          };
        });
      });
      </script>
    </div>
		
    <div class="footer">
			<img src="../images/cnlong.png" alt="Code Nation">
		</div>
	</body>
</html>