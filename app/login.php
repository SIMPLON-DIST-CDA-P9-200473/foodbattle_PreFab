<?php $title='Connexion';require 'includes/header.php';require 'includes/db.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
check_post();
$username=trim($_POST['username']??'');$password=$_POST['password']??'';
$s=$db->prepare('SELECT id,password_hash FROM admins WHERE username=?');$s->execute([$username]);$admin=$s->fetch();
if($admin&&password_verify($password,$admin['password_hash'])){
session_regenerate_id(true);$_SESSION['admin_id']=$admin['id'];header('Location: /dashboard.php');exit;
}
$error='Identifiants incorrects.';
}
?><h1>Connexion</h1><?php if(isset($error)): ?><p role="alert"><?= h($error) ?></p><?php endif ?>
<form method="post" class="reviewform"><input type="hidden" name="token" value="<?= csrf() ?>"><label>Nom d’utilisateur<input name="username" autocomplete="username" required></label><label>Mot de passe<input type="password" name="password" autocomplete="current-password" required></label><button>Se connecter</button></form>
<?php require 'includes/footer.php'; ?>
