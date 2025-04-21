<?php
// Register page content

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php?page=account');
    exit;
}

// Check if redirect parameter exists
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
    $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';
    $errors = [];
    
    // Basic validation
    if (empty($firstName)) {
        $errors[] = "Le prénom est requis.";
    }
    
    if (empty($lastName)) {
        $errors[] = "Le nom est requis.";
    }
    
    if (empty($email)) {
        $errors[] = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }
    
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    
    // If no errors, create user account
    if (empty($errors)) {
        // In a real app, would insert into database
        // For demo, we'll simulate a successful registration
        $_SESSION['user_id'] = 1;
        $_SESSION['user_name'] = $firstName . ' ' . $lastName;
        
        // Redirect user
        if (!empty($redirect)) {
            header("Location: index.php?page={$redirect}");
        } else {
            header('Location: index.php?page=account');
        }
        exit;
    }
}
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Créer un compte</h2>
            <p>Rejoignez MovieSHOP pour profiter de tous les avantages</p>
        </div>
        
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form class="auth-form" method="POST" action="index.php?page=register<?php echo !empty($redirect) ? "&redirect={$redirect}" : ''; ?>">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">Prénom</label>
                    <input type="text" id="firstName" name="firstName" class="form-input" value="<?php echo isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Nom</label>
                    <input type="text" id="lastName" name="lastName" class="form-input" value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : ''; ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-input" required>
                <small>Le mot de passe doit contenir au moins 8 caractères.</small>
            </div>
            
            <div class="form-group">
                <label for="confirmPassword">Confirmer le mot de passe</label>
                <input type="password" id="confirmPassword" name="confirmPassword" class="form-input" required>
            </div>
            
            <div class="form-group terms-checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">J'accepte les <a href="#">conditions générales de vente</a> et la <a href="#">politique de confidentialité</a></label>
            </div>
            
            <button type="submit" class="btn">Créer un compte</button>
        </form>
        
        <div class="auth-links">
            <p>Déjà un compte? <a href="index.php?page=login<?php echo !empty($redirect) ? "&redirect={$redirect}" : ''; ?>">Se connecter</a></p>
        </div>
    </div>
</div>

<style>
    .error-messages {
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    
    .error-messages ul {
        list-style-type: disc;
        margin-left: 20px;
    }
    
    .terms-checkbox {
        display: flex;
        margin-bottom: 20px;
    }
    
    .terms-checkbox input {
        margin-right: 10px;
    }
    
    .terms-checkbox a {
        color: var(--primary-color);
        text-decoration: underline;
    }
    
    .form-group small {
        display: block;
        margin-top: 5px;
        color: #666;
        font-size: 12px;
    }
</style>