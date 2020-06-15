<?php

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
  
      $sql = "INSERT INTO UserFavoritesRecipes VALUES (:username1, $ReID)"; 
        
      $statement = $connection->prepare($sql);
      $statement->bindValue(':username1', $username1);
      $statement->execute();
  
      echo "Recipe successfully added to your favorite list.";
    } catch(PDOException $error) {
      echo "This recipe is already in your favorite list.";
    }
  }

?>

<br><br>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>