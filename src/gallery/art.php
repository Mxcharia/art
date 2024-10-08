<?php
session_start();
if (!isset($_SESSION['user']))
  header("Location: /art/src/login.php");
include '../../lib/services.php';
$user_id = $_SESSION['user']['user_id'];
$services = new Services($user_id);

// Check if art ID is provided
if (isset($_GET['id'])) {
  // Sanitize the input to prevent SQL injection or other attacks
  $art_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  // Fetch art details from the database
  $art_details = $services->selectwhere("art", "id", '=', $art_id);
  $art = mysqli_fetch_assoc($art_details);
} else {
  echo "Art ID not provided";
}
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the form data
  $art_id = $_POST['art_id']; // Assuming you have a hidden input field with the art_id
  $name = $_POST['name'];
  $artist_name = $_POST['artist'];
  $description = $_POST['description'];
  $price = $_POST['price'];

  // File upload handling
  $target_dir = "/srv/http/art/uploads/$user_id/art/"; // Specify the directory where you want to store the uploaded files
  $target_file = $target_dir . basename($_FILES["file-input"]["name"]);
  $file_size = $_FILES["file-input"]["size"];
  $file_name = $_FILES["file-input"]["name"];
  $image_url = $target_dir . $file_name;
  $stripped_target_dir = str_replace("/srv/http", "", $target_dir);
  $image_url = $stripped_target_dir . $file_name;
  $result = $services->selectwhere("gallery", "user_id", '=', $user_id);
  $gallery = mysqli_fetch_assoc($result);
  $gallery_id = $gallery["id"];
  $response =  $services->upload($target_file, $file_size);

  if ($response == 0) {
    $values = array(
      'name' => array('val' => $name, 'type' => 's'),
      'artist' => array('val' => $artist_name, 'type' => 's'),
      'art_url' => array('val' => $image_url, 'type' => 's'),
      'description' => array('val' => $description, 'type' => 's'),
      'price' => array('val' => $price, 'type' => 'i'),
    );

    // Perform the update operation
    $services->updateArt($art_id, $values);

    $success = "Art details updated successfully";
  } else {
    $error = $response;
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="../../assets/images/moon.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="../../assets/css/form.css">
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <title>Register Art</title>
</head>

<body>
  <header>
    <div class="logo">
      <a href="/art/index.html"><img src="../../assets/images/moon.png" alt="Logo"></a>
    </div>
    <div class="title">
      <h1>Fusion</h1>
    </div>
    <nav class="nav-links">
      <a href="/art/src/logout.php">Logout</a>
      <a href="event_upload.php">New Event</a>
      <a href="art_upload.php">Upload Art</a>
      <a href="/art/src/gallery/dashboard.php">Home</a>
    </nav>
  </header>

  <!-- Login form -->
  <div class="form-container">

    <?php if (isset($error)) : ?>
      <center>
        <div class="error-container">
          <span class="error" aria-live="polite"><?php echo $error; ?></span>
        </div>
      </center>
    <?php endif; ?>


    <?php if (isset($success)) : ?>
      <center>
        <div class="error-container">
          <span class="success" aria-live="polite"><?php echo $success; ?></span>
        </div>
      </center>
    <?php endif; ?>


    <form method="post" autocomplete="off" enctype="multipart/form-data">
      <input type="hidden" name="art_id" value="<?php echo $art['id']; ?>">
      <h1>Edit Art Piece</h1>

      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" class="form-control" name="name" placeholder="Enter event name" value="<?php echo isset($art['name']) ? $art['name'] : ''; ?>" />
        <span class="error" aria-live="polite" id="email-error"></span>
      </div>

      <div class="form-group">
        <label for="artist">Artist Name:</label>
        <input type="text" id="artist" class="form-control" name="artist" placeholder="Enter artist name" value="<?php echo isset($art['artist']) ? $art['artist'] : ''; ?>" />
        <span class="error" aria-live="polite" id="email-error"></span>
      </div>
      <div class="form-group">
        <label for="description">Art Description:</label>
        <textarea id="description" name="description" placeholder="Enter event description ..." class="form-control"><?php echo isset($art['description']) ? $art['description'] : ''; ?></textarea>
        <span class="error" aria-live="polite" id="email-error"></span>
      </div>
      <div class="form-group">
        <label for="price">Art Price:</label>
        <input type="number" id="price" class="form-control" name="price" placeholder="Enter event ticket price" min="1" value="<?php echo isset($art['price']) ? $art['price'] : ''; ?>" />
        <span class="error" aria-live="polite" id="email-error"></span>
      </div>
      <center>
        <div class="form-group-image">
          <label for="file-input">
            <p>Upload Art Image</p>
            <span class="fa-stack fa-3x" aria-hidden="true">
              <i class="fas fa-upload fa-stack-1x"></i>
            </span>
          </label>
          <input id="file-input" name="file-input" type="file" />
          <span class="error" aria-live="polite" id="email-error"></span>
        </div>
      </center>
      <button class="submit-btn" name="submit-art">Update Art</button>
    </form>
  </div>
  <script>
    const form = document.querySelector('form')

    function validateInputs() {
      let isValid = true
      const inputs = form.querySelectorAll('input')
      inputs.forEach((input) => {
        if (input.value.trim() === '') {
          input.nextElementSibling.textContent = 'Required'
          input.nextElementSibling.className = 'error active'
          isValid = false
        } else {
          input.nextElementSibling.textContent = ''
          input.nextElementSibling.className = 'error'
        }
      })
      return isValid
    }
    const uploadartButton = document.querySelector('.submit-btn[name="submit-art"]')
    uploadartButton.addEventListener('click', (event) => {
      // Perform validation
      const areInputsValid = validateInputs()

      // If any validation fails, prevent default form submission
      if (!areInputsValid) {
        event.preventDefault() // Prevent default form submission
      }
    })
  </script>
</body>

</html>
