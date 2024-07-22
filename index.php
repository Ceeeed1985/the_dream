<?php

// _________________________________ RECUPERER L'API _____________________________________

$url = "https://currency-converter5.p.rapidapi.com/currency/convert?format=json&from=EUR&amount=1&language=fr";
$headers = [
    "X-RapidAPI-Host: currency-converter5.p.rapidapi.com",
    "X-RapidAPI-Key: 6435e53cd2mshdca89a6d4ef9b2ap120334jsn2fda24fc95bf"
];

$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $data = curl_exec($ch);
    if ($data === false) {
        $error_message = curl_error($ch);
        curl_close($ch);
        die("Erreur cURL: $error_message");
    }
curl_close($ch);

$data_array = json_decode($data, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Erreur de décodage JSON: " . json_last_error_msg());
}


$country = '';
$devise = '';
$money = '';


//RECUPERER LES DONNEES DU FORMULAIRE
if (isset($_POST['country']) && isset($_POST['money'])){
    $country = $_POST['country'];
    $money = $_POST['money'];
    $money = floatval($money);
} else {
    $erreurEncodage = "Veuillez encoder un pays et une somme en euros";
}


//RECUPERER LA DATABASE MYSQL
try {$bdd = new PDO('mysql:host=localhost;dbname=thedream;charset=utf8','root', '');
} catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
}


//CREER LA REQUETE

$requete = $bdd ->prepare('SELECT *
                            FROM countries
                            WHERE paysFr = ? OR paysEn = ?');

$requete -> execute(array($country, $country));

while($donnees = $requete->fetch()){
    $devise = $donnees['codeDevise'];
}

$taux = null;
if(isset($data_array['rates'][$devise])){
    $taux = floatval($data_array['rates'][$devise]['rate']);
} else {
    $taux = null;
}

$valeur_convertie = null;

if($taux !== null){
    $valeur_convertie = $money * $taux;
} else {
    $erreur_impossible = "impossible de calculer la valeur convertie";
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
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if ($taux !== null && $valeur_convertie !== null) {
                                echo 'Le taux de conversion est de : ' . htmlspecialchars($taux) . '.<br>';
                                echo 'Pour ' . htmlspecialchars($money) . ' euros, vous recevrez en échange : ' . htmlspecialchars($valeur_convertie) . ' ' . htmlspecialchars($devise);
                            } else {
                                echo 'Impossible de calculer la valeur convertie sans taux de change.';
                            }
                        }
                        ?>
                    </td>
                </tr>
            </table>
            
        </form>
    </section>
</body>
</html>