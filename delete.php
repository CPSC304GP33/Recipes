<?php include "templates/header.php"; ?>
<?php
if(session_id() == '') {
  session_start();
}

$username1 = "";

require "./config.php";
require "./common.php";

if(!empty($_SESSION['username'])) {
    $username1 = $_SESSION['username'];
    echo "Currently logged in as: ". $username1 . "<br>";
} else {
    echo 'no session';
}

if (isset($_GET["ReID"])) {
  try {
    $connection = new PDO($dsn, $username, $password, $options);

    $ReID = $_GET["ReID"];
    $InstructionID = $_GET["InstructionID"];

    //Get InsID to delete Instruction from Instruction table first

    $sql = "DELETE FROM Instruction WHERE InsID = :InstructionID";

    $statement = $connection->prepare($sql);
    $statement->bindValue(':InstructionID', $InstructionID);
    $statement->execute();

    $deleteInstructionSuccess = "Instruction successfully deleted.";


    $sql = "DELETE FROM Recipe WHERE ReID = :ReID";

    $statement = $connection->prepare($sql);
    $statement->bindValue(':ReID', $ReID);
    $statement->execute();

    $success = "Recipe successfully deleted";
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}

try  {

    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * FROM Recipe AS r, Instruction AS i, RecipeTime AS rt
                    WHERE r.InstructionID = i.InsID AND rt.PrepTime = r.PrepTime
                    AND rt.CookTime = r.CookTime AND r.Username = :username1";


    $statement = $connection->prepare($sql);
    $statement->bindParam(':username1', $username1, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetchAll();

} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

?>
<?php

    if ($result && $statement->rowCount() > 0) { ?>
        <h2>My Recipes</h2>

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
                </tr>
            </thead>
            <tbody>
        <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo $row["ReID"]; ?></td>
                <td><?php echo $row["SkillLevel"]; ?></td>
                <td><?php echo $row["Name"]; ?></td>
                <td><?php echo $row["PrepTime"]; ?></td>
                <td><?php echo $row["CookTime"]; ?></td>
                <td><?php echo $row["TotalTime"]; ?></td>
                <td><?php echo $row["Instructions"]; ?></td>
                <td><?php echo $row["ServingSize"]; ?></td>
                <td><a href="delete.php?ReID=<?php echo escape($row["ReID"]); ?>&InstructionID=<?php echo escape($row["InstructionID"]); ?>">Delete</a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No results found for <?php echo escape($_SESSION['username']); ?>.</blockquote>
    <?php }
 ?>

<br>
<a href="index.php">Back to home</a>


<?php include "templates/footer.php"; ?>
