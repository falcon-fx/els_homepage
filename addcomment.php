<?php
    require_once("./start.php");
    session_start();
    $error = "";
    $team_id = "";
    if(count($_GET) === 1) {
        $team_id = $_GET["id"];
    }
    if(count($_POST) === 1) {
        if(!isset($_POST["commentbox"]) || empty(trim(strip_tags($_POST["commentbox"])))) {
            $_SESSION["error"] = "A komment nem lehet üres!";
            header("Location: ./details.php?id=" . $team_id);
        } else {
            $comm = trim(strip_tags($_POST["commentbox"]));
            $date = date("Y-m-d");
            $commuser = $_SESSION["usr_id"];
            addComment($commuser, $comm, $date, $team_id, $data, $comments);
            header("Location: ./details.php?id=" . $team_id);
        }
    }
    if(count($_GET) === 2) {
        if(!isset($_GET["team"]) || empty(trim($_GET["team"]))) {
            header("Location: ./main.php");
        } else {
            if(!isset($_GET["cid"]) || empty(trim($_GET["cid"]))) {
                header("Location: ./details.php?id=" . $_GET["team"]);
            } else {
                delComment($_GET["cid"], $data, $comments);
                header("Location: ./details.php?id=" . $_GET["team"]);
            }
        }
    }
?>