<?php
$data=array_map('trim', $_POST);
$data=array_map('htmlentities',$data);
require_once 'connect.php';

$pdo = new \PDO(DSN, USER, PASS);

$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll();

if (!empty($data)) {
    $firstname =$data['firstname']; 
    $lastname =$data['lastname'];
    if (empty($firstname)){
        $errors[]='Please enter your firstname';
    }
    if (empty($lastname)){
        $errors[]='Please enter your lastname';
    }
    if (mb_strlen($firstname) > 45 || mb_strlen($lastname) > 45) {
        $errors[]='votre nom ou prenom fait plus de 45 caractères';
    }
}

if (!empty($data) && empty($errors)) {
    $query = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)';
    $statement = $pdo->prepare($query);

    $statement->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
    $statement->bindValue(':lastname', $lastname, \PDO::PARAM_STR);

    $statement->execute();
    header("location: validation.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <?php
    if (!isset($errors)) {
    } else {
        echo "<h2>Please fix errors below</h2>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
    ?>

    <ul>
        <?php
        foreach ($friends as $friend) {
            echo '<li>' . $friend['firstname'] . " " . $friend['lastname'] . '</li>';
        }
        ?>
    </ul>
    <form action="" method="post">
        <label for="firstname">firstname : </label>
        <input type="text" id="firstname" name="firstname" required>
        <label for="lastname">lastname : </label>
        <input type="text" id="lastname" name="lastname" required>
        <input type="submit" value="Send">
    </form>    
</body>
</html>