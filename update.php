<?php

/**
  * List all users with a link to edit
  */

try {
  require "./config.php";
  require "./common.php";

  $connection = new PDO($dsn, $username, $password, $options);

  $sql = "SELECT * FROM Recipe";

  $statement = $connection->prepare($sql);
  $statement->execute();

  $result = $statement->fetchAll();
} catch(PDOException $error) {
  echo $sql . "<br>" . $error->getMessage();
}
?>


<?php require "templates/header.php"; ?>

<h2>Update Recipes</h2>

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
      <td><a href="update-single.php?ReID=<?php echo escape($row["ReID"]); ?>">Edit</a></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<a href="index.php">Back to home</a>
