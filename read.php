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
if (isset($_POST['showall'])) {
    try  {

        include 'config.php';
        require 'common.php';

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * FROM Recipe AS r, Instruction AS i, Recipetime AS rt
                        WHERE r.InstructionID = i.InsID AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime";


        $statement = $connection->prepare($sql);
        $statement->execute();

        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

}

if (isset($_POST['submit'])) {
    try  {

        include 'config.php';
        require 'common.php';

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * FROM Recipe AS r, Instruction AS i, Recipetime AS rt
                        WHERE SkillLevel = :skill AND r.InstructionID = i.InsID
                        AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime";

        $skill = $_POST['skill'];

        $statement = $connection->prepare($sql);
        $statement->bindParam(':skill', $skill, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

}

if (isset($_POST['time'])) {
    try  {

        include 'config.php';
        require 'common.php';

        $connection = new PDO($dsn, $username, $password, $options);
        $val = $_POST['totaltime'];
        
        if ($val == '900000'){
        $sql = "SELECT * FROM recipe AS r, instruction AS i, recipetime AS rt
                        WHERE rt.TotalTime > '010000'  AND r.InstructionID = i.InsID
                        AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime
                        ORDER BY rt.TotalTime DESC";
        }
        else {
        $sql = "SELECT * FROM recipe AS r, instruction AS i, recipetime AS rt
                        WHERE rt.TotalTime <= $val AND r.InstructionID = i.InsID
                        AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime
                        ORDER BY rt.TotalTime DESC";
        }

        $totaltime = $_POST['totaltime'];

        $statement = $connection->prepare($sql);
        $statement->bindParam(':totaltime', $totaltime, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

}

if (isset($_POST['showfavbyall'])) {
    try  {

        include 'config.php';
        require 'common.php';

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * FROM Recipe AS r, Instruction AS i, RecipeTime AS rt
                        WHERE r.InstructionID = i.InsID AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime AND r.ReID = (SELECT r1.ReID FROM Recipe AS r1
                            WHERE NOT EXISTS (
                            (SELECT Username FROM BookUser) 
                            EXCEPT (SELECT ur.Username FROM UserFavoritesRecipes As ur WHERE ur.ReID = r1.ReID)))";

        $statement = $connection->prepare($sql);
        $statement->execute();

        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

}

if (isset($_POST['stime'])) {
    try  {

        include 'config.php';
        require 'common.php';

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT DISTINCT * FROM Recipe AS r, Instruction AS i, Recipetime AS rt, recipehasservingtime AS rst
                        WHERE rst.SName = :servingtime AND rst.reID = r.reID AND r.InstructionID = i.InsID
                        AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime";

        $servingtime = $_POST['servingtime'];

        $statement = $connection->prepare($sql);
        $statement->bindParam(':servingtime', $servingtime, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

}

if (isset($_POST['rtag'])) {
    try  {

        include 'config.php';
        require 'common.php';

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * FROM Recipe AS r, Instruction AS i, Recipetime AS rt, recipelabelledtag AS rtag
                        WHERE rtag.TName = :tag AND rtag.ReID = r.reID AND r.InstructionID = i.InsID
                        AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime";

        $tag = $_POST['tag'];

        $statement = $connection->prepare($sql);
        $statement->bindParam(':tag', $tag, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

}

if (isset($_POST['rcuisine'])) {
    try  {

        include 'config.php';
        require 'common.php';

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * FROM Recipe AS r, Instruction AS i, Recipetime AS rt, recipesincuisine AS rc
                        WHERE rc.CName = :cuisine AND rc.ReID = r.reID AND r.InstructionID = i.InsID
                        AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime";

        $cuisine = $_POST['cuisine'];

        $statement = $connection->prepare($sql);
        $statement->bindParam(':cuisine', $cuisine, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}

if (isset($_POST['rpop'])) {
    try  {

        include 'config.php';
        require 'common.php';

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * FROM Recipe AS r, Instruction AS i, Recipetime AS rt, ratingresult AS rs, averating AS ar, finalrating AS fr
                        WHERE ar.Popularity = :pop AND rs.AverageScore = ar.AverageScore AND fr.RID = rs.RID AND fr.ReID = r.reID AND r.InstructionID = i.InsID
                        AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime";

        $pop = $_POST['pop'];

        $statement = $connection->prepare($sql);
        $statement->bindParam(':pop', $pop, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}

?>



<?php require "templates/header.php"; ?>

<?php

if (isset($_POST['submit']) || isset($_POST['showall']) ||isset($_POST['time']) || isset($_POST['showfavbyall']) || isset($_POST['stime'])  || isset($_POST['rtag'])  || isset($_POST['rcuisine']) || isset($_POST['rpop'])) {
    if ($result && $statement->rowCount() > 0) { ?>
        <h2>Results</h2>
        <?php echo $val; ?>
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
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>Sorry there isn't a recipe for your selection at this time yet.</blockquote>
    <?php }
} ?>

<?php
if (isset($_POST['Search'])) {
    function myTable($obConn,$sql)
{
    $rsResult = mysqli_query($obConn, $sql) or die(mysqli_error($obConn));
    if(mysqli_num_rows($rsResult)>0)
    {
        
        echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\"
        cellpadding=\"0\"><tr align=\"center\" bgcolor=\"#CCCCCC\">";
        $i = 0;
        while ($i < mysqli_num_fields($rsResult)){
            $field = mysqli_fetch_field_direct($rsResult, $i);
            $fieldName=$field->name;
            echo "<td><strong>$fieldName</strong></td>";
            $i = $i + 1;
        }
        echo "</tr>";
        
        while ($row = mysqli_fetch_assoc($rsResult)) {

        foreach($row as $data) {
            echo "<td>$data</td>";
        }
        echo "</tr>";
        }
        echo "</table>";
    }
}
    include 'connect.php';
    $cust_cols_array = $_POST['cols'];
    $cols = implode(",", $cust_cols_array);
    $conn = OpenCon();
    $sql = "SELECT $cols FROM Recipe AS r, Instruction AS i, Recipetime AS rt
            WHERE r.InstructionID = i.InsID AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime";
    myTable($conn,$sql);
}
?>

<h2>Filter Recipes:</h2>

<form method="post">
    <input type="submit" name="showall" value="Show All Recipes"><br>
    <br>
    <input type="submit" name="showfavbyall" value="Show Recipes Favorited by All Users"><br>
    <br>
    <label for="skill">With Skill Level:</label>
    <select name="skill" id="skill">
        <option disabled selected value> -- select an option -- </option>
        <option value="Easy">Easy</option>
        <option value="Medium">Medium</option>
        <option value="Hard">Hard</option>
    </select>
    <br><br>
    <input type="submit" name="submit" value="View Results">
    <br><br>

    <label for="totaltime">With Total Time:</label>
    <select name="totaltime" id="totaltime">
        <option disabled selected value> -- select an option -- </option>
        <option value="001500">Quick (<= 15 minutes)</option>
        <option value="003000">Medium (<= 30 minutes)</option>
        <option value="010000">Dedication (<= 1 hour)</option>
        <option value="900000">Passion (> 1 hour)</option>
    </select>

    <br><br>
    <input type="submit" name="time" value="View Results">
    <br>
    <br>
    <label for="servingtime">With Serving Time:</label>
    <select name="servingtime" id="servingtime">
        <option disabled selected value> -- select an option -- </option>
        <option value="Breakfast">Breakfast</option>
        <option value="Lunch">Lunch</option>
        <option value="Brunch">Brunch</option>
        <option value="Tea-time">Tea-time</option>
        <option value="Dinner">Dinner</option>
        <option value="Dessert">Dessert</option>
        
    </select>

    <br><br>
    <input type="submit" name="stime" value="View Results">
    <br>
    <br>
    <label for="tag">With Tag:</label>
    <select name="tag" id="tag">
        <option disabled selected value> -- select an option -- </option>
        <option value="Low-fat">Low-fat</option>
        <option value="Low-sodium">Low-sodium</option>
        <option value="Sweet">Sweet</option>
        <option value="Vegan">Vegan</option>
        <option value="Vegetarian">Vegetarian</option>
        
    </select>
    <br><br>
    <input type="submit" name="rtag" value="View Results">

    <br>
    <br>
    <label for="cuisine">In Cuisine:</label>
    <select name="cuisine" id="cuisine">
        <option disabled selected value> -- select an option -- </option>
        <option value="French">French</option>
        <option value="Italian">Italian</option>
        <option value="Thai">Thai</option>
        <option value="Western">Western</option>
        <option value="Indian">Indian</option>
        
    </select>
    <br><br>
    <input type="submit" name="rcuisine" value="View Results">

    <br>
    <br>
    <label for="pop">By Popularity:</label>
    <select name="pop" id="pop">
        <option disabled selected value> -- select an option -- </option>
        <option value="Cold">Cold</option>
        <option value="Warmer">Warmer</option>
        <option value="Hot">Hot</option>
        <option value="Hottest">Hottest</option>
        
    </select>
    <br><br>
    <input type="submit" name="rpop" value="View Results">
</form>

<form method="post">
    </br><h3>View Selected Columns:</h3>
    <input type="checkbox" name="cols[]" value="ReID" />ReID<br />
    <input type="checkbox" name="cols[]" value="SkillLevel" />SkillLevel<br />
    <input type="checkbox" name="cols[]" value="Name" />Name<br />
    <input type="checkbox" name="cols[]" value="rt.PrepTime" />PrepTime<br />
    <input type="checkbox" name="cols[]" value="rt.CookTime" />CookTime<br />
    <input type="checkbox" name="cols[]" value="rt.TotalTime" />TotalTime<br />
    <input type="checkbox" name="cols[]" value="Instructions" />Instruction<br />
    <input type="checkbox" name="cols[]" value="ServingSize" />ServingSize<br />
    <input type="checkbox" name="cols[]" value="Username" />Username

    <br><br><input type="submit" name="Search" value="View" />

</form>

<br>
<a href="index.php">Go Back Homepage</a>

<?php require "templates/footer.php"; ?>
