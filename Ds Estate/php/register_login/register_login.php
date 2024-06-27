
<!DOCTYPE html>
<html>
<head>
    <title>Register/Login</title>
    <link rel="stylesheet" href="/DS ESTATE/style/styleRegisterLogin.css">
    
</head>
<body>
    <header id="header1">
        <?php 
            include '../navbar.php'; 
            
        ?>
    </header>
    <section id="registerLogin">
    <h1>Welcome to DS Estate</h1>
        <?php
            if (isset($_SESSION['message'])) {
                echo '<p style="color:black;">' . $_SESSION['message'] . '</p>';
                unset($_SESSION['message']); // Remove message after displaying it
            }
        ?>
        <script>
            function chooseLoginOrRegister(isNewUser) {
                const registerForm = document.getElementById('registrationForm');
                const loginForm = document.getElementById('loginForm');
                
                if (isNewUser) {
                    registerForm.style.display = 'block';
                    loginForm.style.display = 'none';
                } else {
                    registerForm.style.display = 'none';
                    loginForm.style.display = 'block';
                }
            }
        </script>
        <p>Are you new to this page?</p>
        <button onclick="chooseLoginOrRegister(true)">Yes</button>
        <button onclick="chooseLoginOrRegister(false)">No</button>
        
        <!-- Registration Form -->
        <div id="registrationForm" style="display:none;">
            <h2>Register</h2>
            <form action="../../php/register_login/register.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="surname">Surname:</label>
                <input type="text" id="surname" name="surname" required><br><br>

                <label for="client_username">Username:</label>
                <input type="text" id="client_username" name="client_username" required><br><br>

                <label for="code">Code:</label>
                <input type="text" id="code" name="code" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>

                <button type="submit">Register</button>
            </form>
        </div>

        <!-- Login Form -->
        <div id="loginForm" style="display:none;">
            <h2>Login</h2>
            <form action="login.php" method="get">
                <label for="username_email">Username or Email:</label>
                <input type="text" id="username_email" name="username_email" required><br><br>

                <label for="code">Code:</label>
                <input type="text" id="code" name="code" required><br><br>

                <button type="submit">Login</button>
            </form>
        </div>
    </section>
    <footer><?php
        include '../footer.php';
    ?></footer>
</body>
</html>