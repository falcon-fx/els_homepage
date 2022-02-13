<?php
    session_start();
    require_once("./start.php");
    function sortDates($d1, $d2) {
        return strtotime($d2["date"] - $d1["date"]);
    }
    uasort($matches, function($b, $a) {
        return strcmp($a["date"], $b["date"]);
    });
    $success = isset($_SESSION["usr_id"]) ? "Ön be van jelentkezve!" : "Az oldal teljes értékű használatához kérjük jelentkezzen be, vagy regisztráljon!";
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Eötvös Loránd Stadion - Főoldal</h1> 
    <div id="menudiv">
        <ul id="menu">
            <li id="mainpage"><a href="./main.php" target="_self">Főoldal</a></li>
            <li id="loginpage"><a href=<?= isset($_SESSION["usr_id"]) ? "./logout.php" : "./login.php" ?> target="_self"><?= isset($_SESSION["usr_id"]) ? "Kijelentkezés" : "Bejelentkezés" ?></a></li>
            <li id="regpage"><a href="./register.php" target="_self">Regisztráció</a></li>
        </ul>
    </div>
    <h2>Üdvözlet<?= isset($_SESSION["usr_id"]) ? ", " . $_SESSION["usr"] . "!" : "" ?></h2>
    <div id="intro">
        <div id="i1">
            <p>Köszönjük, hogy ellátogatott szerény kis Stadionunk weboldalára! Az oldalon minden futballrajongó megtalálhatja a legfontosabb információkat: Kik játszottak kivel, illetve mikor, és mi lett az adott meccs eredménye.</p>
            <img id="stadionimage" src="./elstadion.png" alt="Az Eötvös Loránd Stadion kivilágítva">
        </div>
        <p><?= $success ?></p>
    </div>
    <?php
        echo("<h2>Csapatok</h2>");
        echo("<div id=\"teams\">");
        echo("<table id=\"teamstable\">");
        echo("<tr id=\"header\"><th>Csapatnév</th><th>Város</th></tr>");
        foreach($teams as $team) {
            echo("<tr><td><a href=\"./details.php?id=" . $team["id"] . "\" target=\"_self\">" . $team["name"] . "</a></td><td>" . $team["city"] . "</td></tr>");
        }
        echo("</table></div>");
        echo("<h2>Meccsek</h2><p>A legutóbbi 5 meccs és részleteik</p>");
        echo("<div id=\"matches\">");
        echo("<table id=\"matchestable\">");
        echo("<tr id=\"header\"><th>Ki</th><th>Kivel</th><th>Mikor</th><th>Eredmény</th></tr>");
        
        for($i = 0; $i < 5; ++$i) {
            $match = $matches[array_keys($matches)[$i]];
            echo("<tr><td>" . $teams[$match["home"]["id"]]["name"] . "</td><td>" . $teams[$match["away"]["id"]]["name"] . "</td><td>" . $match["date"] . "</td><td>" . $match["home"]["score"] . " - " . $match["away"]["score"] ."</td></tr>");
        }
        echo("</table></div>");

    ?>
</body>
</html>