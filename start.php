<?php

require_once("./lib/storage.php");

$data = new Storage(new JsonIO("./data/data.json"));
$teams = $data->findById("teams");
$matches = $data->findById("matches");
$comments = $data->findById("comments");
$users = $data->findById("users");
function styleDatTeam($match, $team) { 
    $won = "class=\"won\"";
    $tie = "class=\"tie\"";
    $lost = "class=\"lost\"";
    if($match["home"]["id"] == $team && $match["home"]["score"] != "" && $match["away"]["score"] != "" && intval($match["home"]["score"]) > intval($match["away"]["score"])) {
        return $won;
    } else if($match["home"]["id"] == $team && $match["home"]["score"] != "" && $match["away"]["score"] != "" && intval($match["home"]["score"]) === intval($match["away"]["score"])) {
        return $tie;
    } else if($match["home"]["id"] == $team && $match["home"]["score"] != "" && $match["away"]["score"] != "" && intval($match["home"]["score"]) < intval($match["away"]["score"])) {
        return $lost;
    } else if($match["away"]["id"] == $team && $match["home"]["score"] != "" && $match["away"]["score"] != "" && intval($match["away"]["score"]) > intval($match["home"]["score"])) {
        return $won;
    } else if($match["away"]["id"] == $team && $match["home"]["score"] != "" && $match["away"]["score"] != "" && intval($match["away"]["score"]) < intval($match["home"]["score"])){
        return $lost;
    } else if($match["away"]["id"] == $team && $match["home"]["score"] != "" && $match["away"]["score"] != "" && intval($match["away"]["score"]) === intval($match["home"]["score"])) {
        return $tie;
    } else {
        return "";
    }
}

function registerUser($id, $uname, $email, $passwd, $admin, $data, $users) {
    $newuser = [];
    $newuser["id"] = $id;
    $newuser["username"] = $uname;
    $newuser["email"] = $email;
    $newuser["password"] = $passwd;
    $newuser["admin"] = $admin;
    $users[$id] = $newuser;
    $data->update("users", $users);
}

function addComment($user, $comment, $date, $team, $data, $comments) {
    $new_id = hash("md5", $user . $comment);
    $newcomment = [];
    $newcomment["author"] = $user;
    $newcomment["text"] = $comment;
    $newcomment["teamid"] = $team;
    $newcomment["date"] = $date;
    $comments[$new_id] = $newcomment;
    $data->update("comments", $comments);
}

function delComment($comment_id, $data, $comments) {
    if(array_key_exists($comment_id, $comments)) {
        unset($comments[$comment_id]);
        $data->update("comments", $comments);
    }
}

function editMatch($match_id, $date, $home_score, $away_score, $data, $matches) {
    if(array_key_exists($match_id, $matches)) {
        $matches[$match_id]["date"] = $date;
        $matches[$match_id]["home"]["score"] = $home_score;
        $matches[$match_id]["away"]["score"] = $away_score;
        $data->update("matches", $matches);
    }
}

function resetMatch($match_id, $data, $matches) {
    if(array_key_exists($match_id, $matches)) {
        editMatch($match_id, $matches[$match_id]["date"], "", "", $data, $matches);
    }
}