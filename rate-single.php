<?php
if(session_id() == '') {
  session_start();
}
$username1 = "";

if(!empty($_SESSION['username'])) {
    $username1 = $_SESSION['username'];
    echo "Currently logged in as: ". $username1 . "<br>";
} else {
    echo 'no session';
}
/**
  * Use an HTML form to edit an entry in the
  * users table.
  *
  */
require "./config.php";
require "./common.php";

if (isset($_POST['submit'])) {
    try {
      $connection = new PDO($dsn, $username, $password, $options);
      $score = $_POST['score'];
      $reid = $_GET['ReID'];
      $sql_get_rating = "SELECT * FROM FinalRating WHERE ReID = $reid AND UserName = '" . $_SESSION['username'] . "'";
      $rating_exist = $connection->prepare($sql_get_rating);

      // $new_score = $_POST["score"];
      // $sql2 = "INSERT INTO FinalRating (Username, ReID, RID, Score)
      //          VALUES ('User6',8,6,'".$_POST['score']."')";
      // $statement = $connection->prepare($sql2);
      // // $statement->bindValue(':score', $new_score);
      $rating_exist->execute();

      // echo $username1. "<br>" ;
      if(!$rating_exist->fetchAll()) {
          //if RID exist in FinalRating, UPDATE AverageScore, NumOfUsers from RatingResult WHERE FinalRating.RID = RatingResult.RID
          $sql_rid_command = "SELECT RID FROM FinalRating WHERE ReID = $reid";
          $sql_rid_value = $connection->prepare($sql_rid_command);
          $sql_rid_value ->execute();

          if($sql_rid_value->fetchAll()) {
              $rid = $sql_rid_value->fetchAll()[0]['RID'];
              echo $rid;
              $sql_sum_command = "SELECT SUM(Score) AS value_sum FROM FinalRating WHERE ReID = $reid";
              $sql_sum_value = $connection->prepare($sql_sum_command);
              $sql_sum_value ->execute();
              $sum = $sql_sum_value->fetchAll()[0]['value_sum'] + $score;

              $sql_count_command = "SELECT COUNT(Score) AS value_count FROM FinalRating WHERE ReID = $reid";
              $sql_count_value = $connection->prepare($sql_count_command);
              $sql_count_value ->execute();
              $count = $sql_count_value->fetchAll()[0]['value_count'] + 1;
              $ave = number_format($sum/$count);

              $sql_update_ave = "UPDATE RatingResult
                                 SET AverageScore = $ave, NumOfUsers = $count
                                 WHERE RID = $rid";
              $connection->prepare($sql_update_ave)->execute();

              $sql_insert = "INSERT INTO FinalRating VALUES ('". $_SESSION['username'] ."', $reid, $rid, $score)";
              $connection->prepare($sql_insert)->execute();
          }else {
              //else INSERT AverageScore, NumOfUsers(1) FROM RatingResult
                //INSERT FinalRating(remember to query RID using MAX(RID))
              $sql_insert_command = "INSERT INTO RatingResult (AverageScore, NumOfUsers)
                                     VALUES ($score, 1)";
              $connection->prepare($sql_insert_command)->execute();
              $sql_insert = "INSERT INTO FinalRating
                             VALUES ('". $_SESSION['username'] ."', $reid, (SELECT MAX(RID) FROM RatingResult),$score)";
              $connection->prepare($sql_insert)->execute();
          }



      } else {
          $sql_update_command = "UPDATE FinalRating
                                     SET score = $score
                                 WHERE ReID = $reid AND UserName = '" . $_SESSION['username'] . "'";
          $sql_update_value = $connection->prepare($sql_update_command);
          $sql_update_value ->execute();

          //grabe the sums of score and amount of users WHERE ReID = FinalRating.$ReID
          $sql_sum_command = "SELECT SUM(Score) AS value_sum FROM FinalRating WHERE ReID = $reid";
          $sql_sum_value = $connection->prepare($sql_sum_command);
          $sql_sum_value ->execute();
          $sum = $sql_sum_value->fetchAll()[0]['value_sum'];

          $sql_count_command = "SELECT COUNT(Score) AS value_count FROM FinalRating WHERE ReID = $reid";
          $sql_count_value = $connection->prepare($sql_count_command);
          $sql_count_value ->execute();
          $count = $sql_count_value->fetchAll()[0]['value_count'];

          //update AverageScore in RatingResult
          $aveScore = number_format($sum/$count);
          $sql_update_ave = "UPDATE RatingResult
                             SET AverageScore = $aveScore
                             WHERE RID = (SELECT DISTINCT RID FROM FinalRating WHERE ReID = $reid)";
          $connection->prepare($sql_update_ave)->execute();
      }
    } catch(PDOException $error) {
      echo $sql_get_rating . "<br>" . $error->getMessage();
    }
}
if (isset($_GET['ReID'])) {
  try {
    $connection = new PDO($dsn, $username, $password, $options);
    $ReID = $_GET['ReID'];
    $sql = "SELECT * FROM Recipe AS r, Instruction AS i, RecipeTime AS rt WHERE r.InstructionID = i.InsID AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime AND r.ReID =:ReID";
    $statement = $connection->prepare($sql);
    $statement->bindValue(':ReID', $ReID);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
} else {
    echo "Something went wrong!";
    exit;
}
?>

<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) : ?>
  Thank you for your rating.
<?php endif; ?>

 <h2>Rate Recipe</h2>
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
                </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo $user["ReID"]; ?></td>
                <td><?php echo $user["SkillLevel"]; ?></td>
                <td><?php echo $user["Name"]; ?></td>
                <td><?php echo $user["PrepTime"]; ?></td>
                <td><?php echo $user["CookTime"]; ?></td>
                <td><?php echo $user["TotalTime"]; ?></td>
                <td><?php echo $user["Instructions"]; ?></td>
                <td><?php echo $user["ServingSize"]; ?></td>
            </tr>
            </tbody>
    </table>
<form method="post" class>
    <br>
    <label> Rating:</label>
    <select name="score" id="score">
      <option disabled selected value> -- select a rating --</option>
      <option value=5>Very Satisfied = 5</option>
      <option value=4>Satisfied = 4</option>
      <option value=3>Neutral = 3</option>
      <option value=2>Dissatisfied = 2</option>
      <option value=1>Very Dissatisfied = 1</option>
    </select>
    <input type="submit" name="submit" value="Submit">
</form>
<br>
<a href="rateRecipes.php">Back to Rate page</a>
<br><br>
<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
