<?php
require "templates/header.php";

if(session_id() == '') {
    session_start();
  }
if(!empty($_SESSION['username'])) {
    $username1 = $_SESSION['username'];
    echo "Currently logged in as: ". $username1 . "<br>";
} else {
    echo 'no session';
}

echo "<br>";

if (isset($_GET["ReID"])) {
    try {
        include 'config.php';
        require 'common.php';

      $connection = new PDO($dsn, $username, $password, $options);
  
      $ReID = $_GET["ReID"];
  
      $sql = "SELECT * FROM Recipe AS r, Instruction AS i, Recipetime AS rt
                        WHERE r.ReID = $ReID AND r.InstructionID = i.InsID AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime";
        
      $statement = $connection->prepare($sql);
      $statement->execute();
      $result = $statement->fetchAll();

    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
  }

?>
<?php
    if ($result && $statement->rowCount() > 0) { ?>
        <h2>Results</h2>

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
                <td><?php echo $row["Username"]; ?></td>
                <td><a href="favoriterecipe.php?ReID=<?php echo escape($row["ReID"]); ?>">Favorite</a></td>
                <td><a href="rate-single.php?ReID=<?php echo escape($row["ReID"]); ?>">Rate</a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>Sorry there isn't a recipe for your selection at this time yet.</blockquote>
    <?php }
?>

<br><br>
<a href="findUser.php">Back to Find User Page</a>
<br><br>
<a href="index.php">Back to Home</a>

<?php require "templates/footer.php"; ?>