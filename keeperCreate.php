<?php
  if (isset($_POST['submit'])) {
    require "config.php";
    require "common.php";

    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_user = array(
        "username" => $_POST['username'],
        "password" => $_POST['password']
      );

      $sql = sprintf(
        "INSERT INTO %s (%s) values (%s)",
        "BookKeeper",
        implode(",", array_keys($new_user)),
        ":" . implode(", :", array_keys($new_user))
      );

      $statement = $connection->prepare($sql);
      $statement->execute($new_user);

    } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
    }
  } ?>

  <?php include "templates/header.php"; ?>
  <?php if (isset($_POST['submit']) && $statement) { ?>
    <?php echo escape($_POST['username']); ?> successfully added.
  <?php } ?>

  <h2>Add a bookkeeper</h2>

  <form method="post">
    <label for="username">Username</label>
    <input type="text" name="username" id="username">
    <label for="password">Password</label>
    <input type="text" name="password" id="password"> <br> <br>
    <input type="submit" name="submit" value="Submit">
  </form>
  <br>
  <a href="logout.php">Login with your new account</a>

<?php include "templates/footer.php"; ?>
