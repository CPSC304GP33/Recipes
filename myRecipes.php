<?php include "templates/header.php"; ?>
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

if (isset($_POST['showall'])) {
    try  {

        include 'config.php';
        require 'common.php';

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * FROM recipe AS r, instruction AS i, recipetime AS rt
                        WHERE r.InstructionID = i.InsID AND rt.PrepTime = r.PrepTime
                        AND rt.CookTime = r.CookTime AND r.Username = :username1";


        $statement = $connection->prepare($sql);
        $statement->bindParam(':username1', $username1, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll();

    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

}

if (isset($_POST['total#'])) {
    try  {

        include 'config.php';
        require 'common.php';

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT COUNT(*) as total FROM recipe WHERE Username = :username1";


        $statement = $connection->prepare($sql);
        $statement->bindParam(':username1', $username1, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchColumn();

    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

}
?>
<?php

if (isset($_POST['showall']) ) {
    if ($result && $statement->rowCount() > 0) { ?>
        <h2>My Recipes</h2>

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
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No results found for <?php echo escape($_SESSION['username']); ?>.</blockquote>
    <?php }
} ?>

<?php
if (isset($_POST['total#']) ) {
    if ($result) {
        echo "Total number of recipes: " . $result . "<br>";
    } else { ?>
        <blockquote>No results found for <?php echo escape($_SESSION['username']); ?>.</blockquote>
    <?php }
} ?>

<form method="post">
  <input type="submit" name = showall value="View All Recipes">
</form>
<form method="post">
  <input type="submit" name = total# value="My Recipe Count">
</form>

<br>
<a href="index.php">Back to home</a>


<?php include "templates/footer.php"; ?>
