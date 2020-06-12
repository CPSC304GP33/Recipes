<?php

/**
  * Delete a user
  */

require "./config.php";
require "./common.php";


if (isset($_GET["ReID"])) {
  try {
    $connection = new PDO($dsn, $username, $password, $options);

    $ReID = $_GET["ReID"];

    //Get InsID to delete Instruction from Instruction table first 
    /*
    $sql = "SELECT InstructionID FROM recipe WHERE ReID =:ReID";

    $statement = $connection->prepare($sql);
    $statement->bindValue(':ReID', $ReID);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC) ;
    */

    $sql = "DELETE FROM recipe WHERE ReID = :ReID";

    $statement = $connection->prepare($sql);
    $statement->bindValue(':ReID', $ReID);
    $statement->execute();

    $success = "Recipe successfully deleted";
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}

try {
  $connection = new PDO($dsn, $username, $password, $options);

  $sql = "SELECT * FROM recipe";

  $statement = $connection->prepare($sql);
  $statement->execute();

  $result = $statement->fetchAll();
} catch(PDOException $error) {
  echo $sql . "<br>" . $error->getMessage();
}
?>
<?php require "templates/header.php"; ?>

<h2>Delete Recipe</h2>

<?php if ($success) echo $success; ?>

<table>
  <thead>
    <tr>
    <th>#</th>
      <th>Skill Level</th>
      <th>User Name</th>
      <th>Prepare Time</th>
      <th>Cook Time</th>
      <th>InstructionID</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($result as $row) : ?>
    <tr>
    <td><?php echo escape($row["ReID"]); ?></td>
      <td><?php echo escape($row["SkillLevel"]); ?></td>
      <td><?php echo escape($row["Name"]); ?></td>
      <td><?php echo escape($row["PrepTime"]); ?></td>
      <td><?php echo escape($row["CookTime"]); ?></td>
      <td><?php echo escape($row["InstructionID"]); ?></td>
      <td><a href="delete.php?ReID=<?php echo escape($row["ReID"]); ?>">Delete</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>