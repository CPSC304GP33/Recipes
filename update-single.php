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
    $user =[
      "ReID"        => $_POST['ReID'],
      "SkillLevel" => $_POST['SkillLevel'],
      "Name"  => $_POST['Name'],
      "PrepTime"     => $_POST['PrepTime'],
      "CookTime"       => $_POST['CookTime'],
      "InstructionID"  => $_POST['InstructionID']
    ];

    $sql = "UPDATE Recipe
            SET ReID = :ReID,
              SkillLevel = :SkillLevel,
              Name = :Name,
              PrepTime = :PrepTime,
              CookTime = :CookTime,
              InstructionID = :InstructionID,
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
    $sql = "SELECT * FROM Recipe WHERE ReID = :ReID";
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
