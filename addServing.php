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
        "Name" => $_POST['name'],
        "SName" => $_POST['servingtime']
      );
      $sql = "SELECT ReID FROM Recipe
      WHERE Name = '".$_POST['name']."' AND Username = '".$_SESSION['username']."'";
      $statement = $connection->prepare($sql);
      $statement->execute($new_cui);
      $result = $statement->fetchColumn();
      if($result) {
          $sql2 = "INSERT INTO RecipeHasServingTime (ReID, SName)
                  VALUES ('$result', '".$_POST['servingtime']."')";
          $statement = $connection->prepare($sql2);
          $statement->execute();
          if($statement){
            echo "Serving time successfully added for ".$_POST['name'].".";
          }
      } else {
        echo "You do not have a recipe with that name.";
      }
    } catch(PDOException $error) {
      // echo $sql . "<br>" . $error->getMessage();
      echo "You have already added that serving time to the specified recipe.";
    }
  }
?>

<h2>Add Another Serving Time to an Existing Recipe</h2>

<form method="post">
  <label for="name">Recipe Name</label>
  <input type="text" name="name" id="name"><br><br>
  <label for="servingtime">Select Serving Time</label>
  <select name="servingtime" id="servingtime">
      <option disabled selected value> -- select an option -- </option>
      <option value="Breakfast">Breakfast</option>
      <option value="Brunch">Brunch</option>
      <option value="Lunch">Lunch</option>
      <option value="Dinner">Dinner</option>
      <option value="Dessert">Dessert</option>
      <option value="Snack">Snack</option>
      <option value="Tea-time">Tea-time</option>
  </select>
  <br><br>
  <input type="submit" name="submit" value="Submit">
</form>
<br><br>
<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
