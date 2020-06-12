<?php

if (isset($_POST['showall'])) {
    try  {
        
        include 'config.php';
        require 'common.php';

        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT * FROM recipe AS r, instruction AS i, recipetime AS rt 
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

        $sql = "SELECT * FROM recipe AS r, instruction AS i, recipetime AS rt
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
?>
<?php require "templates/header.php"; ?>
        
<?php  
if (isset($_POST['submit']) || isset($_POST['showall'])) {
    if ($result && $statement->rowCount() > 0) { ?>
        <h2>Results</h2>

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
        <blockquote>No results found for <?php echo escape($_POST['skill']); ?>.</blockquote>
    <?php } 
} ?> 

<h2>Find recipe based on Skill Level</h2>

<form method="post">
    <input type="submit" name="showall" value="Show All Recipes"><br>
    <br>

    <label for="skill">With Skill Level:</label>
    <select name="skill" id="skill">
        <option disabled selected value> -- select an option -- </option>
        <option value="Easy">Easy</option>
        <option value="Medium">Medium</option>
        <option value="Hard">Hard</option>
    </select>

    <label for="preptime">With PrepTime:</label>
    <select name="preptime" id="preptime">
        <option disabled selected value> -- select an option -- </option>
        <option value="Easy">Easy</option>
        <option value="Medium">Medium</option>
        <option value="Hard">Hard</option>
    </select>


    <input type="submit" name="submit" value="View Results">
</form>

<br>
<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>