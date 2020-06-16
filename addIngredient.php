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
        "Name" => $_POST['ingName'],
        "Unit" => $_POST['ingUnit']
      );

      $sql1 ="SELECT * FROM Ingredient
      WHERE Name = '".$_POST['ingName']."' AND Unit = '".$_POST['ingUnit']."'";
      $statement = $connection->prepare($sql1);
      $statement->execute($new_cui);
      $test = $statement->fetch(PDO::FETCH_ASSOC);
      if(!$test) {
        $sql3 = "INSERT INTO Ingredient (Name, Unit)
                VALUES ('".$_POST['ingName']."', '".$_POST['ingUnit']."')";
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
        "IName" => $_POST['ingName'],
        "Amount" => $_POST['ingAmount']
      );
      $sql = "SELECT ReID FROM Recipe
      WHERE Name = '".$_POST['name']."' AND Username = '".$_SESSION['username']."'";
      $statement = $connection->prepare($sql);
      $statement->execute($new_cui);
      $result = $statement->fetchColumn();

      if($result) {
          $sql2 = "INSERT INTO RecipeContainsIngredient (IName, ReID, Amount)
                  VALUES ('".$_POST['ingName']."', '$result', '".$_POST['ingAmount']."')";
          $statement = $connection->prepare($sql2);
          $statement->execute();
          if($statement){
            echo "Ingredient successfully added for ".$_POST['name'].".";
          }
      } else {
        echo "You do not have a recipe with that name.";
      }
    } catch(PDOException $error) {
      // echo $sql . "<br>" . $error->getMessage();
      echo "You have already added that ingredient to the specified recipe.";
    }
  }
?>

<h2>Add Another Ingredient to an Existing Recipe</h2>

<form method="post">
  <label for="name">Recipe Name</label>
  <input type="text" name="name" id="name"><br><br>
  <label for="ingName">Add Ingredient (Name)</label>
  <input type="text" name="ingName" id="ingName"><br><br>
  <label for="ingUnit">Add an Ingredient (Unit)</label>
  <input type="text" name="ingUnit" id="ingUnit"><br><br>
  <label for="ingAmount">Add an Ingredient (Amount)</label>
  <input type="text" name="ingAmount" id="ingAmount"><br><br>
  <input type="submit" name="submit" value="Submit">
</form>
<br><br>
<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
