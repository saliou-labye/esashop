<?php
include 'db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $query = "SELECT * FROM admins WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: index.php');
        exit;
    } else {
        $message = "Email ou mot de passe incorrect.";
    }
}
?>

<h1>Connexion Administrateur</h1>
<?php if (isset($message)): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<form action="login.php" method="post">
    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required>
    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" name="mot_de_passe" id="mot_de_passe" required>
    <button type="submit">Se connecter</button>
</form>

<?php
include 'footer.php';
?>