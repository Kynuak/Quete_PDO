<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php

    require '_connec.php';
    $pdo = new PDO(DSN, USER, PASS);

    //Récupération des valeurs de la table
    $query = "SELECT * FROM friend";
    $statement = $pdo->query($query);

    //Assignation dans un tableau avec clé pour les champs de la table
    $friends = $statement->fetchAll(PDO::FETCH_ASSOC);

    $errors = [];

    //vérification de l'envoi post
    if(!empty($_POST)) {

        //nettoyage des données
        $data = array_map('trim', $_POST);
        $data = array_map('htmlentities', $_POST);

        //Vérif des inputs du formulaire
        if(!isset($data['firstname']) || empty($data['firstname']) || strlen($data['firstname']) > 45) {

            $errors['firstname'] = "Le champs First name n'est pas rempli ou trop long";
        }

        if(!isset($data['lastname']) || empty($data['lastname']) || strlen($data['lastname']) > 45) {

            $errors['lastname'] = "Le champs Last name n'est pas rempli ou trop long";
        }

        //pas d'erreur envoie dans la BDO
        if(empty($errors)) {
            $query = "INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)";
            $statement = $pdo->prepare($query);

            $statement->bindValue(':firstname', $data['firstname'], PDO::PARAM_STR);
            $statement->bindValue(':lastname',  $data['lastname'], PDO::PARAM_STR);
            $statement->execute();
            header("Location: index.php");
            exit();
        } else {
            //Si erreurs affichage des erreurs
            foreach($errors as $error){
                echo $error . "<br>";
            }
        }

    }

    ?>
    
    <h1>Liste d'amis : </h1>

        <ul>
            <?php 
            foreach($friends as $friend){
                echo "<li>" . $friend['firstname'] . ' ' . $friend['lastname'] ."</li>";
            }
            ?>
        </ul> 





    <form action="" method="POST">

        <label for="firstname">First Name :</label>
        <input type="text" id="firstname" name="firstname" required="true"></input>
        <br>
        <label for="lastname">Last Name :</label>
        <input type="text" id="lastname" name="lastname" required="true"></input>

        <input type="submit"></input>

    </form>
</body>
</html>

