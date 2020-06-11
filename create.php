<?php include "templates/header.php"; ?><h2>Add a new recipe</h2>

<form method="post">
    <label for="name">Recipe Name</label>
    <input type="text" name="name" id="name">
    <label for="skill">Skill Level (easy, medium, hard)</label>
    <input type="text" name="skill" id="skill">
    <label for="preptime">PrepTime (minutes)</label>
    <input type="text" name="preptime" id="preptime">
    <label for="cooktime">CookTime (minutes)</label>
    <input type="text" name="cooktime" id="cooktime">
    <label for="location">Instructions</label>
    <input type="text" name="instructions" id="instructions">
    <input type="submit" name="submit" value="Create">
</form>

<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>