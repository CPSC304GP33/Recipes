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

try  {

    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * FROM Recipe AS r, Instruction AS i, RecipeTime AS rt
                    WHERE r.InstructionID = i.InsID AND rt.PrepTime = r.PrepTime
                    AND rt.CookTime = r.CookTime";


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
                <td><a href="viewrecipeingredients.php?ReID=<?php echo escape($row["ReID"]); ?>">View Ingredients</a></td>
                <td><a href="viewrecipeequipment.php?ReID=<?php echo escape($row["ReID"]); ?>">View Equipment</a></td>
                <td><a href="favoriterecipe.php?ReID=<?php echo escape($row["ReID"]); ?>">Favorite</a></td>
                <td><a href="rate-single.php?ReID=<?php echo escape($row["ReID"]); ?>">Rate</a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No results found for <?php echo escape($_SESSION['username']); ?>.</blockquote>
    <?php }
 ?>

<br>
<a href="rateRecipes.php">Reset</a>
<br><br>
<a href="index.php">Back to Home</a>

<?php include "templates/footer.php"; ?>
