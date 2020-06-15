<?php if(session_id() == '') {
  session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Delicious Recipes App</title>

    <link rel="stylesheet" href="css/style.css" />
  </head>

  <body>
    <h1>Delicious Recipes App</h1>

    <ul>
      <li>
        <a href="create.php"><strong>Create Account for a User</strong></a>
      </li>
      <li>
        <a href="keeperDelete.php"><strong>Delete A Recipe</strong></a>
      </li>
      <p>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
      </p>
    </ul>

    <?php include "templates/footer.php"; ?>

  </body>
</html>
