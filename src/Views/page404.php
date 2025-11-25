<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Page non trouvée</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php
    $path = __DIR__ . "/../src/Views/page404.php";
    var_dump($path);
    ?>
    <h1>Erreur 404</h1>
    <p>La page demandée n’existe pas.</p>
    <a href="index.php?url=home">Retour à l’accueil</a>
</body>

</html>