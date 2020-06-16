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
        "Cuisine" => $_POST['cuisine']
      );
      $sql = "SELECT ReID FROM Recipe
      WHERE Name = '".$_POST['name']."' AND Username = '".$_SESSION['username']."'";
      $statement = $connection->prepare($sql);
      $statement->execute($new_cui);
      $result = $statement->fetchColumn();
      if($result) {
          $sql2 = "INSERT INTO RecipesInCuisine (ReID, CName)
                  VALUES ('$result', '".$_POST['cuisine']."')";
          $statement = $connection->prepare($sql2);
          $statement->execute();
          if($statement){
            echo "Cuisine successfully added for ".$_POST['name'].".";
          }
      } else {
        echo "You do not have a recipe with that name.";
      }
    } catch(PDOException $error) {
      // echo $sql . "<br>" . $error->getMessage();
      echo "You have already added that cuisine to the specified recipe.";
    }
  }
?>

<h2>Add Another Cuisine to an Existing Recipe</h2>

<form method="post">
  <label for="name">Recipe Name</label>
  <input type="text" name="name" id="name"><br><br>
  <label for="cuisine">Select Cuisine</label>
  <select name="cuisine" id="cuisine">
      <option disabled selected value> -- select an option -- </option>
      <option value="British">British</option>
      <option value="Caribbean">Caribbean</option>
      <option value="Chinese">Chinese</option>
      <option value="French">French</option>
      <option value="Greek">Greek</option>
      <option value="Indian">Indian</option>
      <option value="Italian">Italian</option>
      <option value="Japanese">Japanese</option>
      <option value="Korean">Korean</option>
      <option value="Mediterranean">Mediterranean</option>
      <option value="Mexican">Mexican</option>
      <option value="Spanish">Spanish</option>
      <option value="Thai">Thai</option>
      <option value="Vietnamese">Vietnamese</option>
      <option value="Western">Western</option>
  </select>
  <br><br>
  <input type="submit" name="submit" value="Submit">
</form>
<br><br>
<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
