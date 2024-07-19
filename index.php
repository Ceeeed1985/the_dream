<?php

//RECUPERER LES DONNEES DU FORMULAIRE
if (isset($_POST['country']) && isset($_POST['money'])){
    $country = $_POST['country'];
    $money = $_POST['money'];
}
echo $country;

//RECUPERER LA DATABASE MYSQL
try {$bdd = new PDO('mysql:host=localhost;dbname=thedream;charset=utf8','root', '');
} catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
}


$requete = $bdd ->prepare('SELECT *
                            FROM countries
                            WHERE paysFr = ?');

$requete -> execute(array($country));

while($donnees = $requete->fetch()){
    $devise = $donnees['codeDevise'];
    echo '<p> La devise pour '.$country.' est la suivante : '.$devise.'</p>';
}



$data = file_get_contents('api.php');
$result = json_decode($data, true);


// FONCTION POUR CALCULER LE TAUX
function calculerTaux($money1,$taux){
    $money2 = $money1 * $taux;
    return $money2;
}






?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Converter</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
    <h1>Money Converter</h1>
    </header>

    <section class="container">
        <form method="post" action ="index.php">
            <table>
                <tr>
                    <td><p>Encodez le pays</p></td>
                    <td><input type="text" name="country" id="country"/></td>
                </tr>
                <tr>
                    <td><p>Quelle somme souhaitez-vous échanger ?</p></td>
                    <td><input type="text" name="money" id="money"/></td>
                </tr>
                <tr>
                    <td><button type="submit">Convertir</button></td>
                    <td>
                        <?php
                            echo 'hello world';
                        ?>
                    </td>
                </tr>
            </table>
            
        </form>
    </section>


</body>
</html>