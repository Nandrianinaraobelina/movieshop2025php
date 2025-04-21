<?php
// Login page content

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php?page=account');
    exit;
}

// Check if redirect parameter exists
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $errors = [];
    
    // Basic validation
    if (empty($email)) {
        $errors[] = "L'email est requis.";
    }
    
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    }
    
    // If no errors, attempt login
    if (empty($errors)) {
        // In a real app, would validate against database
        // For demo, we'll hardcode a test user
        if ($email === 'test@example.com' && $password === 'password') {
            $_SESSION['user_id'] = 1;
            $_SESSION['user_name'] = 'Test User';
            
            // Redirect user
            if (!empty($redirect)) {
                header("Location: index.php?page={$redirect}");
            } else {
                header('Location: index.php?page=account');
            }
            exit;
        } else {
            $errors[] = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Connexion</h2>
            <p>Connectez-vous à votre compte MovieSHOP</p>
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
        
        <form class="auth-form" method="POST" action="index.php?page=login<?php echo !empty($redirect) ? "&redirect={$redirect}" : ''; ?>">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            
            <div class="form-group remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Se souvenir de moi</label>
            </div>
            
            <button type="submit" class="btn">Se connecter</button>
        </form>
        
        <div class="auth-links">
            <a href="#">Mot de passe oublié?</a>
            <p>Pas encore de compte? <a href="index.php?page=register<?php echo !empty($redirect) ? "&redirect={$redirect}" : ''; ?>">S'inscrire</a></p>
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
    
    .remember-me {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .remember-me input {
        margin-right: 10px;
    }
</style>