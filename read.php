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

if (isset($_POST['time'])) {
    try  {
        
        include 'config.php';
        require 'common.php';

        $connection = new PDO($dsn, $username, $password, $options);
        $val = $_POST['totaltime'];
        
        $sql = "SELECT * FROM recipe AS r, instruction AS i, recipetime AS rt
                        WHERE rt.TotalTime <= $val AND r.InstructionID = i.InsID 
                        AND rt.PrepTime = r.PrepTime AND rt.CookTime = r.CookTime";

        $totaltime = $_POST['totaltime'];

        $statement = $connection->prepare($sql);
        $statement->bindParam(':totaltime', $totaltime, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
        
}
?>

<?php require "templates/header.php"; ?>
        
<?php  

if (isset($_POST['submit']) || isset($_POST['showall']) ||isset($_POST['totaltime']) ) {
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
        <blockquote>No results found for <?php echo escape($_POST['skill']); ?>.</blockquote>
    <?php } 
} ?> 

<?php
if (isset($_POST['Search'])) {
function myTable($obConn,$sql)
{
$rsResult = mysqli_query($obConn, $sql) or die(mysqli_error($obConn));
if(mysqli_num_rows($rsResult)>0)
{
//We start with header. >>>Here we retrieve the field names<<< 
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
//>>>Field names retrieved<<<
//We dump info
$bolWhite=true;
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
$cust_cols = $_POST['cust_cols'];
$where_cols = $_POST['where_cols'];
$conn = OpenCon();
$sql = "select $cust_cols from recipe where SkillLevel = '$where_cols'";
myTable($conn,$sql);
}
?>

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
    <br><br>
    <input type="submit" name="submit" value="View Results">

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
</form>

<br><br>

<form method="post">
</br><h3>View Selected Columns:</h3> 
ReID | SkillLevel | Name | PrepTime | CookTime | TotalTime | Instruction | ServingSize | Username
<br> <br>
<label>Enter Column Name with comma Seprated</label> 
<input name="cust_cols" type="text" placeholder="Type Here">
<br>
<label>Where Skill Level (Easy, Medium, Hard):</label>
<input name="where_cols" type="text" placeholder="Type Here">
<br>
<br>
<input type="submit" name = 'Search' value="Search">
</form>


<br>
<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>