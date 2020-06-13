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

      $sql = "SELECT *
        FROM BookUser
        WHERE username = :username";

      $username = $_POST['username'];

      $statement = $connection->prepare($sql);
      $statement->bindParam(':username', $username, PDO::PARAM_STR);
      $statement->execute();

      $result = $statement->fetchAll();

    } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
    }
  } ?>


<?php include "templates/header.php" ?>

<?php
if (isset($_POST['submit'])) {
  if ($result && $statement->rowCount() > 0) { ?>
    <h2>Results</h2>

    <table>
      <thread>
<tr>
  <th>Username</th>
  <th>Email Address</th>
  <th>Dietary Restrictions</th>
  <th>Preferences</th>
</tr>
    </thead>
    <tbody>
  <?php foreach ($result as $row) {?>
      <tr>
<td><?php echo escape($row["Username"]); ?></td>
<td><?php echo escape($row["Email"]); ?></td>
<td><?php echo escape($row["DietaryRestrictions"]); ?></td>
<td><?php echo escape($row["Preferences"]); ?></td>
      </tr>
    <?php } ?>
      </tbody>
  </table>
  <?php } else { ?>
    > No results found for <?php echo escape($_POST['username']); ?>.
  <?php }
} ?>

  <h2>Find user based on username</h2>

  <form method="post">
    <label for="username">Username</label>
    <input type="text" id="username" name="username">
    <input type="submit" name="submit" value="View Results">
  </form>

  <a href="index.php">Back to home</a>

<?php include "templates/footer.php" ?>
