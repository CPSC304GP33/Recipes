<?php include "templates/header.php"; ?>
<?php
  if (isset($_POST['submit'])) {
    require "config.php";
    require "common.php";

    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_ins = array(
        "Instructions" => $_POST['ins'],
        "ServingSize" => $_POST['ss']
      );

      $sql1 = sprintf(
        "INSERT INTO %s (%s) values (%s)",
        "Instruction",
        implode(",", array_keys($new_ins)),
        ":" . implode(", :", array_keys($new_ins))
      );

      $statement = $connection->prepare($sql1);
      $statement->execute($new_ins);
      $last_id = $connection->lastInsertId();

    } catch(PDOException $error) {
      echo $sql1 . "<br>" . $error->getMessage();
    }
    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_time = array(
        "PrepTime" => $_POST['pt'],
        "CookTime" => $_POST['ct'],
        "TotalTime" => $_POST['tt']
      );


      $sql2 = "INSERT INTO RecipeTime (PrepTime, CookTime, TotalTime)
      VALUES ('".$_POST['pt']."', '".$_POST['ct']."', '".$_POST['tt']."')";

      $statement = $connection->prepare($sql2);
      $statement->execute($new_time);

    } catch(PDOException $error) {
      // echo $sql2 . "<br>" . $error->getMessage();
    }
    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_rec = array(
        "SkillLevel" => $_POST['sl'],
        "Name" => $_POST['name'],
        "PrepTime" => $_POST['pt'],
        "CookTime" => $_POST['ct'],
      );

      $sql3 = "INSERT INTO Recipe (SkillLevel, Name, PrepTime, CookTime, InstructionID)
      VALUES ('".$_POST['sl']."','".$_POST['name']."', '".$_POST['pt']."', '".$_POST['ct']."', $last_id)";

      $statement = $connection->prepare($sql3);
      $statement->execute($new_rec);

      if (isset($_POST['submit']) && $statement) {
        echo "<br>";
        echo escape($_POST['name']);
        echo " successfully added." . "<br>";
      }

    } catch(PDOException $error) {
      echo $sql3 . "<br>" . $error->getMessage();
    }
  } ?>

  <h2>Add a recipe</h2>

  <form method="post">
    <label for="name">Recipe Name</label>
    <input type="text" name="name" id="name">
    <label for="sl">Skill Level (Easy, Medium, Hard)</label>
    <input type="text" name="sl" id="sl">
    <label for="pt">Preparation Time (HHMMSS)</label>
    <input type="Integer" name="pt" id="pt">
    <label for="ct">Cooking Time (HHMMSS)</label>
    <input type="Integer" name="ct" id="ct">
    <label for="tt">Total Time (HHMMSS)</label>
    <input type="integer" name="tt" id="tt">
    <label for="ss">Serving Size</label>
    <input type="integer" name="ss" id="ss">
    <label for="ins">Instructions</label>
    <input type="text" name="ins" id="ins">
    <input type="submit" name="submit" value="Submit">
  </form>

  <a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
