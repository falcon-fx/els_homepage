<?php
    
    require_once("./start.php");
    session_start();
    $errors = [];
    $goods = [];
    $mindenJoE = false;
    $badcolor = "color: red; text-shadow: -1px 1px 0 #000, 1px 1px 0 #000, 1px -1px 0 #000, -1px -1px 0 #000;";
    if(count($_POST) > 0) {
        $mindenJoE = true;
        $new_id = hash("md5", $_POST["uname"]);
        if(!isset($_POST["uname"]) || empty(trim(strip_tags($_POST["uname"]))))  {
            $errors["uname"] = "A felhasználónév megadása kötelező!";
            $mindenJoE = false;
        } else if(!isset($_POST["uname"]) || array_key_exists($new_id, $users) || array_keys(array_filter($users, fn($usr) => $usr["username"] == $_POST["uname"]))) {
            $errors["uname"] = "Ilyen felhasználó már létezik! Adjon meg egy másik felhasználónevet!";
            $mindenJoE = false;
        } else {
            $goods["uname"] = $_POST["uname"];
        }
        if(!isset($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Helytelen, vagy hiányzó E-mail cím!";
            $mindenJoE = false;
        } else {
            $goods["email"] = $_POST["email"];
        }
        if(!isset($_POST["passwd"]) || !isset($_POST["passwd2"]) || strlen($_POST["passwd"]) == 0 || strlen($_POST["passwd2"]) == 0 || strcmp($_POST["passwd"], $_POST["passwd2"]) != 0) {
            $errors["passwd"] = "A jelszavak nem egyeznek, vagy nem adott meg jelszót!";
            $mindenJoE = false;
        } else {
            $goods["passwd"] = $_POST["passwd"];
        }
        if($mindenJoE) {
            $new_pw = password_hash($_POST["passwd"], PASSWORD_DEFAULT);
            $new_user = [];
            registerUser($new_id, $_POST["uname"], $_POST["email"], $new_pw, false, $data, $users);
            $goods = [];
            $errors = [];
        }
    }
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Eötvös Loránd Stadion - Regisztráció</h1>
    <div id="menudiv">
        <ul id="menu">
            <li id="mainpage"><a href="./main.php" target="_self">Főoldal</a></li>
            <li id="loginpage"><a href=<?= isset($_SESSION["usr_id"]) ? "./logout.php" : "./login.php" ?> target="_self"><?= isset($_SESSION["usr_id"]) ? "Kijelentkezés" : "Bejelentkezés" ?></a></li>
            <li id="regpage"><a href="./register.php" target="_self">Regisztráció</a></li>
        </ul>
    </div>
    <div id="regdiv">
        <form action="register.php" method="post" novalidate>
            <ul>
                <li id="authli">
                    <label for="uname">Felhasználónév</label>
                    <input type="text" name="uname" id="uname_i" value=<?= array_key_exists("uname", $goods) ? $goods["uname"] : "" ?>>
                    <?= array_key_exists("uname", $errors) ? "<span style=\"". $badcolor . "\">" . $errors["uname"] . "</span>" : "" ?>
                </li>
                <li id="authli">
                    <label for="email">E-mail cím</label>
                    <input type="email" name="email" id="email_i" value=<?= array_key_exists("email", $goods) ? $goods["email"] : "" ?>>
                    <?= array_key_exists("email", $errors) ? "<span style=\"". $badcolor . "\">" . $errors["email"] . "</span>" : "" ?>
                </li>
                <li id="authli">
                    <label for="passwd">Jelszó</label>
                    <input type="password" name="passwd" id="passwd_i">
                </li>
                <li id="authli">
                    <label for="passwd2">Jelszó újra</label>
                    <input type="password" name="passwd2" id="passwd2_i">
                    <?= array_key_exists("passwd", $errors) ? "<span style=\"". $badcolor . "\">" . $errors["passwd"] . "</span>" : "" ?>
                </li>
                <li>
                    <input type="submit" value="Regisztráció">
                    <?php
                        if($mindenJoE) {
                            header("Location: ./login.php");
                        }
                    ?>
                </li>
            </ul>
        </form>
    </div>
</body>
</html>