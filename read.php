<?php include "templates/header.php"; ?>

    <h2>Find recipe based on skill level</h2>

    <form method="post">
    	<label for="skill">Skill Level (easy, medium, hard)</label>
    	<input type="text" id="skill" name="skill">
    	<input type="submit" name="submit" value="View Results">
    </form>

    <a href="index.php">Back to home</a>

    <?php include "templates/footer.php"; ?>