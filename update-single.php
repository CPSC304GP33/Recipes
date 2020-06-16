
<?php

/**
  * Use an HTML form to edit an entry in the
  * users table.
  *
  */
require "./config.php";
require "./common.php";
if (isset($_POST['submit'])) {
    try {
        $connection = new PDO($dsn, $username, $password, $options);
      $equipment_name = array(
          "EName"    => $_POST['EName']
      );
      $recipe_equipment = array(
          "EName"    => $_POST['EName'],
          "ReID"     => $_POST['ReID']
      );

      $sql ="SELECT * FROM Equipment WHERE Name = :EName";
      $statement = $connection->prepare($sql);
      $statement->execute($equipment_name);
      if(!$statement->fetch(PDO::FETCH_ASSOC)) {
          $sql2 = "INSERT INTO Equipment (Name)
                  VALUES ('".$_POST['EName']."');
                  INSERT INTO RecipeUsesEquipment(EName, ReID)
                  VALUES ('".$_POST['EName']."', '".$_POST['ReID']."')";
          $statement = $connection->prepare($sql2);
          $statement->execute($recipe_equipment);
          echo "Equipment not exist";
      } else {
          echo "Equipment already exist";
          $sql ="SELECT * FROM RecipeUsesEquipment WHERE EName =:EName AND ReID =:ReID";
          $statement = $connection->prepare($sql);
          $statement->execute($recipe_equipment);

          if(!$statement) {
              $sql2 = "INSERT INTO RecipeUsesEquipment(EName, ReID)
                      VALUES ('".$_POST['EName']."', '".$_POST['ReID']."')";
              $statement = $connection->prepare($sql2);
              $statement->execute($recipe_equipment);
              echo "RecipeUsesEquipment not exist";
          } else {
              echo "RecipeUsesEquipment already exist";
          }
      }
    } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
    }
    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_time = array(
          "PrepTime"     => $_POST['PrepTime'],
          "CookTime"       => $_POST['CookTime']
      );
      $sql ="SELECT * FROM RecipeTime WHERE PrepTime =:PrepTime AND CookTime =:CookTime";
      $statement = $connection->prepare($sql);
      $statement->execute($new_time);
      $time_exist = $statement->fetch(PDO::FETCH_ASSOC);
      if(!$time_exist) {
          $time = strtotime($_POST['CookTime']) + strtotime($_POST['PrepTime']) - strtotime('00:00:00');
          $totaltime = date('H:i:s', $time);
          $sql2 = "INSERT INTO RecipeTime (PrepTime, CookTime, TotalTime)
                  VALUES ('".$_POST['PrepTime']."', '".$_POST['CookTime']."', '$totaltime')";
          $statement = $connection->prepare($sql2);
          $statement->execute();
      } else {
          echo "RecipeTime already exist";
      }
    } catch(PDOException $error) {
      // echo $sql . "<br>" . $error->getMessage();
    }
  try { //# of parameters in try must be the same in the $sql, in this case,
        //we have 8 parameters in try and $sql
    $connection = new PDO($dsn, $username, $password, $options);
    $user =[
      "ReID"    => $_POST['ReID'],
      "SkillLevel"    => $_POST['SkillLevel'],
      "Name"       => $_POST['Name'],
      "PrepTime"     => $_POST['PrepTime'],
      "CookTime"       => $_POST['CookTime'],
      "Instructions"       => $_POST['Instructions'],
      "ServingSize"       => $_POST['ServingSize']
    ];
    $sql = "UPDATE Instruction
                SET Instructions = :Instructions,
                ServingSize = :ServingSize
            WHERE InsID = (SELECT Recipe.InstructionID FROM Recipe WHERE ReID = :ReID);
            UPDATE Recipe
                SET SkillLevel = :SkillLevel,
                Name = :Name,
                PrepTime = :PrepTime,
                CookTime = :CookTime
            WHERE ReID = :ReID";
  $statement = $connection->prepare($sql);
  $statement->execute($user);
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
}
if (isset($_GET['ReID'])) {
  try {
    $connection = new PDO($dsn, $username, $password, $options);
    $ReID = $_GET['ReID'];
    $sql = "SELECT r.ReID, r.SkillLevel, r.Name, r.PrepTime, r.CookTime, i.Instructions, i.ServingSize
            FROM Recipe AS r, Instruction AS i, RecipeTime AS rt, RecipeUsesEquipment AS re
            WHERE r.InstructionID = i.InsID AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime AND re.ReID = r.ReID AND r.ReID =:ReID";
    $statement = $connection->prepare($sql);
    $statement->bindValue(':ReID', $ReID);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
} else {
    echo "Something went wrong!";
    exit;
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) : ?>
  <?php echo escape($_POST['Name']); ?> successfully updated.
<?php endif; ?>

<h2>Edit a Recipe</h2>

<form method="post">
    <?php foreach ($user as $key => $value) : ?>
      <label for="<?php echo $key; ?>"><?php echo ucfirst($key); ?></label>
      <input type="text" name="<?php echo $key; ?>" ReID="<?php echo $key; ?>" value="<?php echo escape($value); ?>" <?php echo ($key === 'ReID' ? 'readonly' : null); ?>>
    <?php endforeach; ?>
    <label for="name">Equipment</label>
    <input type="text" name="EName" id="EName" value="<?php echo escape($_GET['EName']); ?>">
    <input type="submit" name="submit" value="Submit">
</form>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
