<?php

//Ici on appel le fichier function.php, où il y a toutes les fonctions.
require './function.php';

// l'url de l'api
$apiUrl = 'https://api-developers.spinalcom.com/api/v1/geographicContext/space';

// Récupérer les données de l'api
$jsonData = file_get_contents($apiUrl);


// On check si on récupère bien l'api
if ($jsonData === false) {
    die("Erreur de la récupération de données de l'API.");
}

$data = json_decode($jsonData, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Occupation du bâtiment</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets\css\styles.css" rel="stylesheet">

    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">

    <style>

    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">

    
    <div id="accordion">
   <?php echo genereAccordion($data); ?>

        
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>




</body>
</html>
