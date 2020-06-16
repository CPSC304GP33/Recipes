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

      $new_tag = array(
        "Name" => $_POST['name'],
        "Tag" => $_POST['tag']
      );
      $sql = "SELECT ReID FROM Recipe
      WHERE Name = '".$_POST['name']."' AND Username = '".$_SESSION['username']."'";
      $statement = $connection->prepare($sql);
      $statement->execute($new_tag);
      $result = $statement->fetchColumn();
      if($result) {
          $sql2 = "INSERT INTO RecipeLabelledTag (TName, ReID)
                  VALUES ('".$_POST['tag']."', '$result')";
          $statement = $connection->prepare($sql2);
          $statement->execute();
          if($statement){
            echo "Tag successfully added for ".$_POST['name'].".";
          }
      } else {
        echo "You do not have a recipe with that name.";
      }
    } catch(PDOException $error) {
      // echo $sql . "<br>" . $error->getMessage();
      echo "You have already added that tag to the specified recipe.";
    }
  }
?>

<h2>Add a Tag to an Existing Recipe</h2>

<form method="post">
  <label for="name">Recipe Name</label>
  <input type="text" name="name" id="name"><br><br>
  <label for="tag">Select Tag</label>
  <select name="tag" id="tag">
      <option disabled selected value> -- select an option -- </option>
      <option value="Appetizer">Appetizer</option>
      <option value="Beverage">Beverage</option>
      <option value="Gluten-free">Gluten-free</option>
      <option value="Low-calorie">Low-calorie</option>
      <option value="Low-fat">Low-fat</option>
      <option value="Low-sodium">Low-sodium</option>
      <option value="Main Dish">Main Dish</option>
      <option value="Pescatarian">Pescatarian</option>
      <option value="Salad">Salad</option>
      <option value="Sauce">Sauce</option>
      <option value="Side Dish">Side Dish</option>
      <option value="Soup">Soup</option>
      <option value="Sweet">Sweet</option>
      <option value="Vegan">Vegan</option>
      <option value="Vegetarian">Vegetarian</option>
  </select>
  <br><br>
  <input type="submit" name="submit" value="Submit">
</form>
<br><br>
<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
