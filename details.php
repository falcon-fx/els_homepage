<?php
    require_once("./start.php");
    session_start();
    $team = [];
    $teammatches = [];
    $teamcomments = [];
    $exists = false;
    $badcolor = "color: red; text-shadow: -1px 1px 0 #000, 1px 1px 0 #000, 1px -1px 0 #000, -1px -1px 0 #000;";
    if(count($_GET) === 1) {
        if(isset($_GET["id"]) && in_array($_GET["id"], array_keys($teams))) {
            $team = $teams[$_GET["id"]];
            $exists = true;
            foreach($matches as $match) {
                if($match["home"]["id"] == $team["id"] || $match["away"]["id"] == $team["id"]) {
                    array_push($teammatches, $match);
                }
            }
            foreach($comments as $comment) {
                if($comment["teamid"] == $team["id"]) {
                    array_push($teamcomments, $comment);
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Csapatrészletek - <?=$exists ? $team["name"] : "Nincs csapat"?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Eötvös Loránd Stadion - <?=$exists ? $team["name"] : "Csapatinfó"?></h1>
    <div id="menudiv">
        <ul id="menu">
            <li id="mainpage"><a href="./main.php" target="_self">Vissza a Főoldalra</a></li>
        </ul>
    </div>
    <h2><?=$exists ? $team["name"] : "A csapat"?> meccsei</h2>
    <div id="details">
        <table id="dettable">
            <tr id="header">
                <th>Ki</th>
                <th>Kivel</th>
                <th>Meccs dátuma</th>
                <th>Eredmény</th>
                <?= isset($_SESSION["admin"]) && $_SESSION["admin"] == true ? "<th>Adminisztráció</th>" : "" ?>
            </tr>
            <?php foreach($teammatches as $match): ?>
                <?=$exists ? "<tr><td " . styleDatTeam($match, $match["home"]["id"]) . ">" . $teams[$match["home"]["id"]]["name"] . "</td><td " . styleDatTeam($match, $match["away"]["id"]) . ">" . $teams[$match["away"]["id"]]["name"] . "</td><td>" . $match["date"] . "</td><td>" . $match["home"]["score"] . " - " . $match["away"]["score"] ."</td>" : ""?>
                <?=$exists && isset($_SESSION["admin"]) && $_SESSION["admin"] == true ? "<td><a href=\"editmatch.php?team=" . $team["id"] . "&id=" . $match["id"] . "\">Szerkesztés</a></td>" : ""?>
                <?=$exists ? "</tr>" : "" ?>
            <?php endforeach ?>
        </table>
        <p>Jelmagyarázat: <span class="won">Győztes</span> - <span class="lost">Vesztes</span> - <span class="tie">Döntetlen</span> - <span>Még nem lejátszott</span></p>
    </div>
    <h2>Hozzászólások</h2>
    <div id="comments">
        <table id="commtable">
            <tr id="header">
                <th>Név</th>
                <th>Hozzászólás</th>
                <th>Hozzászólás dátuma</th>
                <?= isset($_SESSION["admin"]) && $_SESSION["admin"] == true ? "<th>Adminisztráció</th>" : "" ?>
            </tr>
            <?php foreach($teamcomments as $comment): ?>
                <?=$exists ? "<tr><td>" . $users[$comment["author"]]["username"] . "</td><td>" . strip_tags($comment["text"]) . "</td><td>" . strip_tags($comment["date"]) . "</td>" : ""?>
                <?=$exists && isset($_SESSION["admin"]) && $_SESSION["admin"] == true ? "<td><a href=\"addcomment.php?team=" . $team["id"] . "&cid=" . array_search($comment, $comments) . "\">Törlés</a></td>" : "" ?>
                <?=$exists ? "</tr>" : "" ?>
            <?php endforeach ?>
        </table>
        <form action=<?= $exists ? ("\"./addcomment.php?id=" . $team["id"] . "\"") : "" ?> method="POST" novalidate>
            <?php
                if(isset($_SESSION["usr_id"]) && array_key_exists($_SESSION["usr_id"], $users)) {
                    echo("<label for=\"commentbox\">Hozzászólás írása</label><br>");
                    echo("<textarea name=\"commentbox\" id=\"commentbox\" maxlength=\"10000\" rows=\"4\" cols=\"60\"></textarea><br>");
                    echo("<input type=\"submit\">");
                    if(isset($_SESSION["error"])) {
                        echo("<span style=\"" . $badcolor . "\">" . $_SESSION["error"] . "</span>");
                        unset($_SESSION["error"]);
                    }
                } else {
                    echo("<h3>Hozzászólás írásához be kell jelentkezni!</h3>");
                }
            ?>
        </form>
    </div>
</body>
</html>