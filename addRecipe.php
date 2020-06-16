<?php include "templates/header.php"; ?>
<?php
  if(session_id() == '') {
    session_start();
  }

  if(!empty($_SESSION['username'])) {
     $username = $_SESSION['username'];
     echo "Currently logged in as: ". $username . "<br>";
  } else{
     echo 'no session';
  }


  if (isset($_POST['submit'])) {
    require "config.php";
    require "common.php";

    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_ins = array(
        "Instructions" => $_POST['ins'],
        "ServingSize" => $_POST['ss']
      );

      $sql1 = sprintf(
        "INSERT INTO %s (%s) values (%s)",
        "Instruction",
        implode(",", array_keys($new_ins)),
        ":" . implode(", :", array_keys($new_ins))
      );

      $statement = $connection->prepare($sql1);
      $statement->execute($new_ins);
      $last_id = $connection->lastInsertId();

    } catch(PDOException $error) {
      echo $sql1 . "<br>" . $error->getMessage();
    }
    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_time = array(
        "PrepTime" => $_POST['pt'],
        "CookTime" => $_POST['ct']
      );
      $time = strtotime($_POST['ct']) + strtotime($_POST['pt']) - strtotime('00:00:00');
      $totaltime = date('H:i:s', $time);


      $sql2 = "INSERT INTO RecipeTime (PrepTime, CookTime, TotalTime)
      VALUES ('".$_POST['pt']."', '".$_POST['ct']."', '$totaltime')";

      $statement = $connection->prepare($sql2);
      $statement->execute($new_time);

    } catch(PDOException $error) {
      // echo $sql2 . "<br>" . $error->getMessage();
    }
    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_rec = array(
        "SkillLevel" => $_POST['sl'],
        "Name" => $_POST['name'],
        "PrepTime" => $_POST['pt'],
        "CookTime" => $_POST['ct'],
      );

      $sql3 = "INSERT INTO Recipe (SkillLevel, Name, PrepTime, CookTime, InstructionID, Username)
      VALUES ('".$_POST['sl']."','".$_POST['name']."', '".$_POST['pt']."', '".$_POST['ct']."', $last_id, '".$_SESSION['username']."')";

      $statement = $connection->prepare($sql3);
      $statement->execute($new_rec);

      if (isset($_POST['submit']) && $statement) {
        echo escape($_POST['name']);
        echo " successfully added." . "<br>";

      }
      $last_id = $connection->lastInsertId();

    } catch(PDOException $error) {
      echo $sql3 . "<br>" . $error->getMessage();
    }
    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_add = array(
        "ServingTime" => $_POST['stime'],
        "Cuisine" => $_POST['cuisine'],
        "Equipment" => $_POST['tool']
      );

      $sql4 = "INSERT INTO RecipeHasServingTime (ReID, SName)
              VALUES ('$last_id','".$_POST['stime']."');

              INSERT INTO RecipesInCuisine (ReID, CName)
              VALUES ('$last_id','".$_POST['cuisine']."');

              INSERT INTO Equipment (Name)
              VALUES ('".$_POST['tool']."')";

      if($last_id) {
        $statement = $connection->prepare($sql4);
        $statement->execute($new_add);
      }

    } catch(PDOException $error) {
      echo $sql4 . "<br>" . $error->getMessage();
    }
    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_add1 = array(
        "IngredientName" => $_POST['ingName'],
        "IngredientUnit" => $_POST['ingUnit'],
        "IngredientAmount" => $_POST['ingAmount'],
        "Equipment" => $_POST['tool']
      );

      $sql5 = "INSERT INTO RecipeUsesEquipment (EName, ReID)
              VALUES ('".$_POST['tool']."', '$last_id');

              INSERT INTO Ingredient (Name, Unit)
              VALUES ('".$_POST['ingName']."','".$_POST['ingUnit']."')";

      if($last_id) {
        $statement = $connection->prepare($sql5);
        $statement->execute($new_add1);
      }

    } catch(PDOException $error) {
      echo $sql5 . "<br>" . $error->getMessage();
    }
    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_add2 = array(
        "IngredientName" => $_POST['ingName'],
        "IngredientAmount" => $_POST['ingAmount']
      );

      $sql6 = "INSERT INTO RecipeContainsIngredient (IName, ReID, Amount)
              VALUES ('".$_POST['ingName']."', '$last_id', '".$_POST['ingAmount']."')";

      if($last_id) {
        $statement = $connection->prepare($sql6);
        $statement->execute($new_add2);
      }

    } catch(PDOException $error) {
      echo $sql6 . "<br>" . $error->getMessage();
    }
    try {
      $connection = new PDO($dsn, $username, $password, $options);

      $new_add3 = array(
        "Icon" => $_POST['icon']
      );

      $sql7 = "INSERT INTO RecipeIcon (ReID, Link)
              VALUES ('$last_id', '".$_POST['icon']."')";

      if($last_id && !empty($_POST['icon'])) {
        $statement = $connection->prepare($sql7);
        $statement->execute($new_add3);
      }

    } catch(PDOException $error) {
      echo $sql7 . "<br>" . $error->getMessage();
    }
  } ?>

  <h2>Add a recipe</h2>
  <h3>Please fill out all of the fields below:</h3>

  <form method="post">
    <label for="name">Recipe Name</label>
    <input type="text" name="name" id="name"><br><br>
    <label for="sl">Skill Level</label>
    <select name="sl" id="sl">
        <option disabled selected value> -- select an option -- </option>
        <option value="Easy">Easy</option>
        <option value="Medium">Medium</option>
        <option value="Hard">Hard</option>
    </select>
    <br><br>
    <label for="pt">Preparation Time (HHMMSS)</label>
    <input type="Integer" name="pt" id="pt"><br><br>
    <label for="ct">Cooking Time (HHMMSS)</label>
    <input type="Integer" name="ct" id="ct"><br><br>
    <label for="ss">Serving Size</label>
    <input type="integer" name="ss" id="ss"><br><br>
    <label for="ins">Instructions</label>
    <textarea rows="10" cols="50" type="text" name="ins" id="ins"></textarea> <br><br>
    <label for="servingtime">Add Serving Time</label>
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
    <label for="cuisine">Add Cuisine</label>
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
    <label for="ingName">Add an Ingredient (Name)</label>
    <input type="text" name="ingName" id="ingName"><br><br>
    <label for="ingUnit">Add an Ingredient (Unit)</label>
    <input type="text" name="ingUnit" id="ingUnit"><br><br>
    <label for="ingAmount">Add an Ingredient (Amount)</label>
    <input type="text" name="ingAmount" id="ingAmount"><br><br>
    <label for="tool">Add a Tool</label>
    <input type="text" name="tool" id="tool"><br><br>
    <label for="icon">Optional: Add an Image (link)</label>
    <input type="text" name="icon" id="icon"><br><br>
    <input type="submit" name="submit" value="Submit">
  </form>
  <br>
  <h3>More Options:</h3>
  <a href="addCuisine.php">Add Another Cuisine</a> <br>
  <a href="addServing.php">Add Another Serving Time</a> <br>
  <a href="addIcon.php">Add Another Image</a> <br>
  <a href="addIngredient.php">Add Another Ingredient</a> <br>
  <a href="addTag.php">Add Tags</a><br>
  <a href="addTool.php">Add Another Tool</a>
  <br><br><br>
  <a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
