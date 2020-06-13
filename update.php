<?php

if(session_id() == '') {
  session_start();
}

if(!empty($_SESSION['username'])) {
   $username = $_SESSION['username'];
   echo "Currently logged in as: ". $username . "<br>";
} else{
   echo 'no session';
}
try {
  require "./config.php";
  require "./common.php";

  $connection = new PDO($dsn, $username, $password, $options);

  $sql = "SELECT * FROM Recipe AS r, Instruction AS i, RecipeTime AS rt
  WHERE r.InstructionID = i.InsID AND rt.TimeKey = r.TimeKey";

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
        <th>ReID</th>
        <th>Skill Level</th>
        <th>Name</th>
        <th>PrepTime</th>
        <th>CookTime</th>
        <th>TotalTime</th>
        <th>Instruction</th>
        <th>Serving Size</th>
        <th>Author</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($result as $row) : ?>
    <tr>
        <td><?php echo $row["ReID"]; ?></td>
        <td><?php echo $row["SkillLevel"]; ?></td>
        <td><?php echo $row["Name"]; ?></td>
        <td><?php echo $row["PrepTime"]; ?></td>
        <td><?php echo $row["CookTime"]; ?></td>
        <td><?php echo $row["TotalTime"]; ?></td>
        <td><?php echo $row["Instructions"]; ?></td>
        <td><?php echo $row["ServingSize"]; ?></td>
        <td><?php echo $row["Username"]; ?></td>
      <td><a href="update-single.php?ReID=<?php echo escape($row["ReID"]); ?>">Edit</a></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<a href="index.php">Back to home</a>
