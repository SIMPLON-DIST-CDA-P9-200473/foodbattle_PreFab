<?php
if (session_status() === PHP_SESSION_NONE) session_start();require 'includes/functions.php';require 'includes/db.php';check_post();
$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
$s=$db->prepare('SELECT id FROM dishes WHERE id=?');$s->execute([$id]);if(!$s->fetch()){http_response_code(404);exit('Plat introuvable.');}
if(($_POST['action']??'')==='vote'){
    // Si la base plante, on prévient le visiteur au lieu d'afficher une page d'erreur
    try{
        $s=$db->prepare('INSERT INTO votes(dish_id,visitor_key) VALUES(?,?) ON CONFLICT DO NOTHING');$s->execute([$id,session_id()]);
    }catch(PDOException $e){
        error_log('Vote impossible : '.$e->getMessage()); // détail technique dans les logs serveur, jamais à l'écran
        header('Location: /plat.php?id='.$id.'&vote=erreur');exit;
    }
    // 0 ligne insérée = ce visiteur avait déjà voté pour ce plat (contrainte UNIQUE)
    if($s->rowCount()===0){header('Location: /plat.php?id='.$id.'&vote=deja');exit;}
    // Vote enregistré : redirection propre au vote (l'avis a la sienne, avis=1)
    header('Location: /plat.php?id='.$id.'&vote=ok');exit;
}elseif(($_POST['action']??'')==='rate'){
    $score=filter_input(INPUT_POST,'score',FILTER_VALIDATE_INT);$nick=trim($_POST['nickname']??'');$comment=trim($_POST['comment']??'');
    if(!$score||$score<1||$score>5||$nick===''||strlen($nick)>240||$comment===''||strlen($comment)>4000){http_response_code(422);exit('Vérifie les champs de ton avis.');}
    // Modération : un nouvel avis est "en_attente", il ne s'affiche qu'après validation par un admin
    $s=$db->prepare("INSERT INTO ratings(dish_id,nickname,score,comment,status) VALUES(?,?,?,?,'en_attente')");$s->execute([$id,$nick,$score,$comment]);
    // Redirection propre à l'avis : le message affiché ne sera pas le même que pour un vote
    header('Location: /plat.php?id='.$id.'&avis=1');exit;
}else{http_response_code(400);exit('Action inconnue.');}
