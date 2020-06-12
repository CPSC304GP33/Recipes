<?php
  if (isset($_POST['submit'])) {
    require "config.php";
    require "common.php";

    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_ins = array(
        "InsID" => $_POST['iID'],
        "Instructions" => $_POST['ins']
      );

      $sql1 = sprintf(
        "INSERT INTO %s (%s) values (%s)",
        "Instruction",
        implode(",", array_keys($new_ins)),
        ":" . implode(", :", array_keys($new_ins))
      );

      $statement = $connection->prepare($sql1);
      $statement->execute($new_ins);

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

      $ptT = strtotime($_POST['pt']);

      $sql2 = "INSERT INTO RecipeTime (PrepTime, CookTime, TotalTime)
      VALUES ($ptT, ".$_POST['ct'].", ".$_POST['tt'].")";

      $statement = $connection->prepare($sql2);
      $statement->execute($new_time);

    } catch(PDOException $error) {
      echo $sql2 . "<br>" . $error->getMessage();
    }
    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_rec = array(
        "SkillLevel" => $_POST['sl'],
        "Name" => $_POST['name'],
        "PrepTime" => $_POST['pt'],
        "CookTime" => $_POST['ct'],
        "InstructionID" => $_POST['iID']
      );

      $sql3 = sprintf(
        "INSERT INTO %s (%s) values (%s)",
        "Recipe",
        implode(",", array_keys($new_rec)),
        ":" . implode(", :", array_keys($new_rec))
      );

      $statement = $connection->prepare($sql3);
      $statement->execute($new_rec);

    } catch(PDOException $error) {
      echo $sql3 . "<br>" . $error->getMessage();
    }
  } ?>

  <?php include "templates/header.php"; ?>
  <?php if (isset($_POST['submit']) && $statement) { ?>
    <?php echo escape($_POST['name']); ?> successfully added.
  <?php } ?>

  <h2>Add a recipe</h2>

  <form method="post">
    <label for="name">Recipe Name</label>
    <input type="text" name="name" id="name">
    <label for="sl">Skill Level (Easy, Medium, Hard)</label>
    <input type="text" name="sl" id="sl">
    <label for="pt">Preparation Time ('HH-MM-SS')</label>
    <input type="string" name="pt" id="pt">
    <label for="ct">Cooking Time ('HH-MM-SS')</label>
    <input type="string" name="ct" id="ct">
    <label for="tt">Total Time ('HH-MM-SS')</label>
    <input type="string" name="tt" id="tt">
    <label for="iID">Instruction ID (Integer)</label>
    <input type="text" name="iID" id="iID">
    <label for="ins">Instructions</label>
    <input type="text" name="ins" id="ins">
    <input type="submit" name="submit" value="Submit">
  </form>

  <a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
