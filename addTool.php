<?php include "templates/header.php"; ?>
<?php
  if(session_id() == '') {
    session_start();
  }

  if(!empty($_SESSION['username'])) {
     $username1 = $_SESSION['username'];
     echo "Currently logged in as: ". $username1 . "<br>";
  } else{
     echo 'no session';
  }

  if (isset($_POST['submit'])) {
    require "config.php";
    require "common.php";

    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_cui = array(
        "EName" => $_POST['tool']
      );

      $sql1 ="SELECT * FROM Equipment WHERE Name = '".$_POST['tool']."'";
      $statement = $connection->prepare($sql1);
      $statement->execute($new_cui);
      $test = $statement->fetch(PDO::FETCH_ASSOC);
      if(!$test) {
        $sql3 = "INSERT INTO Equipment (Name)
                VALUES ('".$_POST['tool']."')";
        $statement = $connection->prepare($sql3);
        $statement->execute();
      }
    } catch(PDOException $error) {
      // echo $sql1 . "<br>" . $error->getMessage();
    }
    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_cui = array(
        "Name" => $_POST['name'],
        "EName" => $_POST['tool']
      );
      $sql = "SELECT ReID FROM Recipe
      WHERE Name = '".$_POST['name']."' AND Username = '".$_SESSION['username']."'";
      $statement = $connection->prepare($sql);
      $statement->execute($new_cui);
      $result = $statement->fetchColumn();

      if($result) {
          $sql2 = "INSERT INTO RecipeUsesEquipment (EName, ReID)
                  VALUES ('".$_POST['tool']."', '$result')";
          $statement = $connection->prepare($sql2);
          $statement->execute();
          if($statement){
            echo "Tool successfully added for ".$_POST['name'].".";
          }
      } else {
        echo "You do not have a recipe with that name.";
      }
    } catch(PDOException $error) {
      // echo $sql . "<br>" . $error->getMessage();
      echo "You have already added that tool to the specified recipe.";
    }
  }
?>

<h2>Add Another Tool to an Existing Recipe</h2>

<form method="post">
  <label for="name">Recipe Name</label>
  <input type="text" name="name" id="name"><br><br>
  <label for="tool">Add Tool</label>
  <input type="text" name="tool" id="tool"><br><br>
  <input type="submit" name="submit" value="Submit">
</form>
<br><br>
<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
