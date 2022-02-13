<?php
    require_once("./start.php");
    session_start();
    $success = "";
    $errors = [];
    $goods = [];
    $username = "";
    $badcolor = "color: red; text-shadow: -1px 1px 0 #000, 1px 1px 0 #000, 1px -1px 0 #000, -1px -1px 0 #000;";
    $uid = "";
    if(count($_POST) > 0 && !isset($_SESSION["usr_id"])) {
        $mindenJoE = true;
        $passwd_decoded = "";
        if(!isset($_POST["uname"]) || empty(trim(strip_tags($_POST["uname"])))) {
            $_SESSION["error"] = "A felhasználónév megadása kötelező!";
            $mindenJoE = false;
        } else if(!isset($_POST["uname"]) || count(array_keys(array_filter($users, fn($usr) => $usr["username"] == $_POST["uname"]))) == 0) {
            $_SESSION["error"] = "A felhasználó nem létezik!";
            $mindenJoE = false;
        } else {
            $uid = array_keys(array_filter($users, fn($usr) => $usr["username"] == $_POST["uname"]))[0];
            $goods["uname"] = $_POST["uname"];
            $username = $_POST["uname"];
        if(isset($_POST["passwd"]) && $users[$uid]["admin"] == true && $_POST["passwd"] === "admin") {
            unset($_SESSION["error"]);
            $_SESSION["admin"] = true;
            $_SESSION["usr_id"] = $uid;
            $_SESSION["usr"] = $users[$uid]["username"];
            header("Location: ./main.php");
        } else if(isset($_POST["passwd"]) && password_verify($_POST["passwd"], $users[$uid]["password"])) {
            unset($_SESSION["error"]);
            if($users[$uid]["admin"] == true) {
                $_SESSION["admin"] = true;
            }
            $_SESSION["usr_id"] = $uid;
            $_SESSION["usr"] = $users[$uid]["username"];
            header("Location: ./main.php");
            } else if(!isset($_POST["passwd"]) || empty(trim(strip_tags($_POST["passwd"])))) {
                $_SESSION["error"] = "A jelszót be kell írni!";
            } else {
                $_SESSION["error"] = "Hibás jelszó!";
            }
        }
        if($mindenJoE) {
            $goods = [];
            $errors = [];
        }
    } else if(isset($_SESSION["usr_id"])) {
        $_SESSION["error"] = "Már be van jelentkezve.";
    }

?>

<!DOCTYPE html> 
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Eötvös Loránd Stadion - Bejelentkezés</h1>
    <div id="menudiv">
        <ul id="menu">
            <li id="mainpage"><a href="./main.php" target="_self">Főoldal</a></li>
            <li id="loginpage"><a href=<?= isset($_SESSION["usr_id"]) ? "./logout.php" : "./login.php" ?> target="_self"><?= isset($_SESSION["usr_id"]) ? "Kijelentkezés" : "Bejelentkezés" ?></a></li>
            <li id="regpage"><a href="./register.php" target="_self">Regisztráció</a></li>
        </ul>
    </div>
    <div id="logindiv">
        <form action="login.php" method="post" novalidate>
            <ul>
                <li id="authli">
                    <label for="uname">Felhasználónév</label>
                    <input type="text" name="uname" id="uname_i" value=<?= $username ?>>
                    <?= isset($_SESSION["error"]) ? "<span style=\"". $badcolor . "\">" . (str_contains($_SESSION["error"], "felhasználó") ? $_SESSION["error"] : "") . "</span>" : "" ?>
                </li>
                <li id="authli">
                    <label for="passwd">Jelszó</label>
                    <input type="password" name="passwd" id="passwd_i">
                    <?= array_key_exists("passwd", $errors) ? "<span style=\"". $badcolor . "\">" . $errors["passwd"] . "</span>" : "" ?>
                    <?= isset($_SESSION["error"]) ? "<span style=\"". $badcolor . "\">" . (str_contains($_SESSION["error"], "jelszó") ? $_SESSION["error"] : "") . "</span>" : "" ?>
                </li>
                <li>
                    <input type="submit" value="Bejelentkezés">
                    <?= isset($_SESSION["error"]) ? "<span style=\"". $badcolor . "\">" . (str_contains($_SESSION["error"], "jelentkezve") ? $_SESSION["error"] : "") . "</span>" : "" ?>
                </li>
            </ul>
        </form>
    </div>
</body>
</html>