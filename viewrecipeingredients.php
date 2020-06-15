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
  
      $sql = "SELECT ri.IName, ri.Amount, i.Unit FROM recipe AS r, recipecontainsingredient AS ri, ingredient AS i
                       WHERE r.ReID = $ReID AND r.ReID = ri.ReID AND ri.IName = i.Name";
        
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
        <h2>My Recipes</h2>

        <table>
            <thead>
                <tr>
                    <th>Ingredient Name</th>
                    <th>Amount</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo $row["IName"]; ?></td>
                <td><?php echo $row["Amount"]; ?></td>
                <td><?php echo $row["Unit"]; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No results found.</blockquote>
    <?php }
 ?>

<br><br>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>