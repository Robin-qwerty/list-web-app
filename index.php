<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

     require_once 'private/dbconnect.php';
     session_start();

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="media/favicon.ico">
    <link rel="manifest" href="manifest.json">
    <title>home - list</title>
    <link href="style/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.0-beta.3/dist/iconify-icon.min.js"></script>
<head>
<body>

    <?php
        $home = 'index.php?page=list';

        // include 'includes/navbar.inc.php';

        try {
            if (isset($page)) {
              $file = 'includes/'.$page.'.inc.php';
              if (file_exists($file)) {
                require_once $file;
              } else {
                header("Location: index.php?page=404");
                exit();
              }
            } else {
              require_once 'includes/home.inc.php';
            }
          } catch (\Exception $e) {
            echo '<meta http-equiv="refresh" content="0; url='.$home.'" />';
          }
    ?>

</body>
</html>
