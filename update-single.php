
<?php

/**
  * Use an HTML form to edit an entry in the
  * users table.
  *
  */
require "./config.php";
require "./common.php";
if (isset($_POST['submit'])) {
  try { //# of parameters in try must be the same in the $sql, in this case,
        //we have 8 parameters in try and $sql
    $connection = new PDO($dsn, $username, $password, $options);
    $user =[
      "ReID"    => $_POST['ReID'],
      "SkillLevel"    => $_POST['SkillLevel'],
      "Name"       => $_POST['Name'],
      "PrepTime"     => $_POST['PrepTime'],
      "CookTime"       => $_POST['CookTime'],
      "TotalTime"       => $_POST['TotalTime'],
      "Instructions"       => $_POST['Instructions'],
      "ServingSize"       => $_POST['ServingSize']
    ];

    $sql = "UPDATE Recipe
                SET SkillLevel = :SkillLevel,
                Name = :Name
            WHERE ReID = :ReID;
            UPDATE Instruction
                SET Instructions = :Instructions,
                ServingSize = :ServingSize
            WHERE InsID = (SELECT Recipe.InstructionID FROM Recipe WHERE ReID = :ReID);
            UPDATE RecipeTime
                SET PrepTime = :PrepTime,
                CookTime = :CookTime,
                TotalTime = :TotalTime
            WHERE TimeKey = (SELECT Recipe.TimeKey FROM Recipe WHERE ReID = :ReID)";
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
    $sql = "SELECT r.ReID, r.SkillLevel, r.Name, rt.PrepTime, rt.CookTime, rt.TotalTime, i.Instructions, i.ServingSize FROM Recipe AS r, Instruction AS i, RecipeTime AS rt WHERE r.InstructionID = i.InsID AND rt.TimeKey = r.TimeKey AND r.ReID =:ReID";
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
    <input type="submit" name="submit" value="Submit">
</form>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
