<?php

if (isset($_POST['submit'])) {
    try  {
        
        include 'config.php';
        require 'common.php';

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * 
                        FROM recipe
                        WHERE SkillLevel = :skill";

        $skill = $_POST['skill'];

        $statement = $connection->prepare($sql);
        $statement->bindParam(':skill', $skill, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
        
}
?>
<?php require "templates/header.php"; ?>
        
<?php  
if (isset($_POST['submit'])) {
    if ($result && $statement->rowCount() > 0) { ?>
        <h2>Results</h2>

        <table>
            <thead>
                <tr>
                    <th>ReID</th>
                    <th>SkillLevel</th>
                    <th>Name</th>
                    <th>PrepTime</th>
                    <th>CookTime</th>
                    <th>InstructionID</th>
                    
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
                <td><?php echo $row["InstructionID"]; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No results found for <?php echo escape($_POST['skill']); ?>.</blockquote>
    <?php } 
} ?> 

<h2>Find user based on skill</h2>

<form method="post">
    <label for="skill">Skill Level (Easy, Medium, Hard)</label>
    <input type="text" id="skill" name="skill">
    <input type="submit" name="submit" value="View Results">
</form>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>