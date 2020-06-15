<?php if(session_id() == '') {
  session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Welcome</title>
</head>
<body>
  <h1>Welcome</h1>
  <h2>This is a delicious recipes app</h2>
  <a href= "login.php" class="button">I am a user</a> <br> <br>
  <a href= "keeperlogin.php" class="button">I am a bookkeeper</a>
  <?php include "templates/footer.php"; ?>
</body>
</html>
