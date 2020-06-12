<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Delicious Recipes App</title>

    <link rel="stylesheet" href="css/style.css" />
  </head>

  <body>
    <h1>Delicious Recipes App</h1>

    <ul>
      <li>
        <a href="create.php"><strong>Create User Account</strong></a>
      </li>
      <li>
        <a href="addRecipe.php"><strong>Add New Recipe</strong></a>
      </li>
      <li>
        <a href="read.php"><strong>Filter Recipe</strong></a>
      </li>
      <li>
        <a href="findUser.php"><strong>Find a User</strong></a>
      </li>
      <li>
         <a href="update.php"><strong>Update Recipe</strong></a>
      </li>
      <li>
      <a href="delete.php"><strong>Delete A Recipe</strong></a> 
      </li>
    </ul>

    <?php include "templates/footer.php"; ?>

  </body>
</html>
