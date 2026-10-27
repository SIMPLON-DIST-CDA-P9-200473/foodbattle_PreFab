<?php $title='Connexion';require 'includes/header.php';require 'includes/db.php'; 
if($_SERVER['REQUEST_METHOD']==='POST'){
check_post();
$username=trim($_POST['username']??'');$password=$_POST['password']??'';
$s=$db->prepare('SELECT id,password_hash FROM admins WHERE username=?');$s->execute([$username]);$admin=$s->fetch(); // requête préparée : protège contre l'injection SQL
if($admin&&password_verify($password,$admin['password_hash'])){ // password_verify compare le mot de passe tapé au hash stocké en base
session_regenerate_id(true);$_SESSION['admin_id']=$admin['id'];header('Location: /dashboard.php');exit; // nouvel identifiant de session à la connexion (protège contre la fixation de session)
}
$error='Identifiants incorrects.'; // même message que le nom ou le mot de passe soit faux : on ne révèle pas quels comptes existent
}
?><h1>Connexion</h1><?php if(isset($error)): ?><p role="alert"><?= h($error) ?></p><?php endif ?>
<form method="post" class="reviewform"><input type="hidden" name="token" value="<?= csrf() ?>"><label>Nom d’utilisateur<input name="username" autocomplete="username" required></label><label>Mot de passe<input type="password" name="password" autocomplete="current-password" required></label><button>Se connecter</button></form>
<?php require 'includes/footer.php'; ?>
