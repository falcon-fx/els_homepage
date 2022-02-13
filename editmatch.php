<?php
    require_once("./start.php");
    $exists = false;
    $badcolor = "color: red; text-shadow: -1px 1px 0 #000, 1px 1px 0 #000, 1px -1px 0 #000, -1px -1px 0 #000;";
    
    $mindenJoE = false;
    if(count($_GET) > 1 && count($_GET) < 4) {
        
        $match = [];
        $goods = [];
        $errors = [];
        $team = [];
        if(!isset($_GET["team"]) || empty(trim($_GET["team"])) || !array_key_exists($_GET["team"], $teams)) {
            header("Location: ./main.php");
        } else {
            if(!isset($_GET["id"]) || empty(trim($_GET["team"])) || !array_key_exists($_GET["id"],$matches)) {
                header("Location: ./details.php?id=" . $_GET["team"]);
            } else {
                $exists = true;
                $team = $teams[$_GET["team"]];
                $match = $matches[$_GET["id"]];
                if(isset($_GET["reset"]) && $_GET["reset"] == true) {
                    resetMatch($match["id"], $data, $matches);
                    header("Location: ./details.php?id=" . $_GET["team"]);
                }
                $goods["datee"] = $match["date"];
                $goods["home_score"] = $match["home"]["score"];
                $goods["away_score"] = $match["away"]["score"];
            }
        }
    } else if(count($_GET) === 1) {
        header("Location: ./main.php");
    }
    if(count($_POST) > 0) {
        $futureCheckOK = true;
        $mindenJoE = true;
        if(!isset($_POST["datee"]) || count(explode("-", $_POST["datee"])) != 3 || !checkdate(intval(explode("-", $_POST["datee"])[1]), intval(explode("-", $_POST["datee"])[2]), intval(explode("-", $_POST["datee"])[0]))) {
            $errors["datee"] = "Hibás dátum!";
            $mindenJoE = false;
            $futureCheckOK = false;
        } else {
            $goods["datee"] = $_POST["datee"];
        }

        if(!isset($_POST["home_score"]) || intval($_POST["home_score"]) < 0 || intval($_POST["home_score"]) > 1000 || (floatval($_POST["home_score"]) != intval($_POST["home_score"])) || $_POST["home_score"] == "") {
            $errors["home_score"] = "Az eredmény megadása kötelező, és csak pozitív egész szám lehet! (0-1000)";
            $mindenJoE = false;
        } else {
            $goods["home_score"] = $_POST["home_score"];
        }
        if(!isset($_POST["away_score"]) || intval($_POST["away_score"]) < 0 || intval($_POST["away_score"]) > 1000 || (floatval($_POST["away_score"]) != intval($_POST["away_score"])) || $_POST["away_score"] == "") {
            $errors["away_score"] = "Az eredmény megadása kötelező, és csak pozitív egész szám lehet! (0-1000)";
            $mindenJoE = false;
        } else {
            $goods["away_score"] = $_POST["away_score"];
        }
        if(isset($_POST["datee"]) && count(explode("-", $_POST["datee"])) == 3 && checkdate(intval(explode("-", $_POST["datee"])[1]), intval(explode("-", $_POST["datee"])[2]), intval(explode("-", $_POST["datee"])[0])) && date("Y-m-d") < $_POST["datee"] && (isset($_POST["home_score"]) || isset($_POST["away_score"])) && ($_POST["home_score"] != "" || $_POST["away_score"] != "")) {
            $errors["home_score"] = "Jövőbeli meccsnek nem lehet eredménye!";
            $errors["away_score"] = "Jövőbeli meccsnek nem lehet eredménye!";
            $mindenJoE = false;
            $futureCheckOK = false;
        } else if(isset($_POST["datee"]) && count(explode("-", $_POST["datee"])) == 3 && checkdate(intval(explode("-", $_POST["datee"])[1]), intval(explode("-", $_POST["datee"])[2]), intval(explode("-", $_POST["datee"])[0])) && date("Y-m-d") < $_POST["datee"] && (isset($_POST["home_score"]) && isset($_POST["away_score"])) && $_POST["home_score"] == "" && $_POST["away_score"] == "") {
            unset($errors["home_score"]);
            unset($errors["away_score"]);
            if($futureCheckOK && !$mindenJoE) {
                $goods["home_score"] = "";
                $goods["away_score"] = "";
                $mindenJoE = true;
            }
        }
        if($mindenJoE) {
            editMatch($match["id"], $goods["datee"], $goods["home_score"], $goods["away_score"], $data, $matches);
            header("Location: ./details.php?id=" . $team["id"]);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Meccs szerkesztő</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Adminisztrációs felület - Meccseredmény szerkesztése</h1>
    <div id="editdiv">
        <form action=<?= $exists ? "\"./editmatch.php?team=" . $team["id"] . "&id=" . $match["id"] . "\"" : "\"\"" ?> method="POST" novalidate>
            <ul>
                <li id="editli">
                    <label for="datee">Dátum módosítása</label>
                    <input type="date" name="datee" id="date_i" value=<?= array_key_exists("datee", $goods) ? $goods["datee"] : "" ?>>
                    <?= array_key_exists("datee", $errors) ? "<span style=\"". $badcolor . "\">" . $errors["datee"] . "</span>" : "" ?>
                </li>
                <li id="editli">
                    <label for="home_score">Hazai csapat állása</label>
                    <input type="number" name="home_score" id="home_score_i" value=<?= array_key_exists("home_score", $goods) ? $goods["home_score"] : "" ?> max="1000">
                    <?= array_key_exists("home_score", $errors) ? "<span style=\"". $badcolor . "\">" . $errors["home_score"] . "</span>" : "" ?>
                </li>
                <li id="editli">
                    <label for="away_score">Ellenfél csapat állása</label>
                    <input type="number" name="away_score" id="away_score_i" value=<?= array_key_exists("away_score", $goods) ? $goods["away_score"] : "" ?> max="1000">
                    <?= array_key_exists("away_score", $errors) ? "<span style=\"". $badcolor . "\">" . $errors["away_score"] . "</span>" : "" ?>
                </li>
                <li>
                    <input type="submit" value="Mentés">
                    <a id="back" href=<?= $exists ? "\"./editmatch.php?team=" . $team["id"] . "&id=" . $match["id"] . "&reset=true\"" : "" ?>>Eredmények törlése</a>
                    <a id="back" href=<?= $exists ? "\"./details.php?id=" . $team["id"] . "\"" : "\"./main.php\"" ?>>Vissza</a>
                    <?php
                        if($mindenJoE && $exists) {
                            header("Location: ./details.php?id=" . $team["id"]);
                        }
                    ?>
                </li>
            </ul>
        </form>
    </div>
</body>
</html>