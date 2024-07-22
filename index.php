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

// FONCTION POUR CALCULER LE TAUX
function calculerTaux($money1,$taux){
    $money2 = $money1 * $taux;
    return $money2;
}


//RECUPERER LES DONNEES DU FORMULAIRE
if (isset($_POST['country']) && isset($_POST['money'])){
    $country = $_POST['country'];
    $money = $_POST['money'];
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
    // echo '<p> La devise pour '.$country.' est la suivante : '.$devise.'</p>';
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
                            echo '<p> La devise pour '.$country.' est la suivante : '.$devise.'</p>';
                        ?>
                    </td>
                </tr>
            </table>
            
        </form>
    </section>
    <section class="container2">
        <h2>Taux de change</h2>
        <ul>
            <?php
            // Afficher le taux de change pour quelques devises
            if (isset($data_array['base_currency_name']) && isset($data_array['rates'])) {
                echo "<p>Devise de base : " . htmlspecialchars($data_array['base_currency_name']) . "</p>";
                echo "<ul>";
                foreach ($data_array['rates'] as $currency_code => $rate_info) {
                    echo "<li>{$currency_code}: Taux = " . htmlspecialchars($rate_info['rate']) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Les données de l'API sont manquantes.</p>";
            }
            ?>
        </ul>
    </section>

</body>
</html>