<?php if(session_id() == '') {
  session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Delicious Recipe App</title>
  <link rel="stylesheet" href="css/style.css" />
<style>

h1,h2,a {
  color: white;
  margin-left: 40px;
  font-family: sans-serif;
}

body{
  background-image: url(img/foodbackgroundimg.jpg);
  background-repeat: no-repeat;
  background-size: cover;
}

</style>
</head>

<body>
  <h1>Welcome to Delicious Recipes App</h1>
  <h2>A place to share your passion for cooking</h2>
  <a href= "login.php" class="button">I am a user</a> <br> <br>
  <a href= "keeperlogin.php" class="button">I am a bookkeeper</a>

  <?php include "templates/footer.php"; ?>

</body>
</html>
