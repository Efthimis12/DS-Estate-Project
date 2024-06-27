<?php
include '../connection.php';

function generateUniqueId($length = 10) {
    return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
}

function showMessage($message, $type) {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <style>
            .error-message {
                color: red;
                background-color: #f8d7da;
                border: 1px solid #f5c6cb;
                padding: 10px;
                margin: 10px 0;
                border-radius: 5px;
                font-family: Arial, sans-serif;
                text-align: center;
            }
            .success-message {
                color: green;
                background-color: #d4edda;
                border: 1px solid #c3e6cb;
                padding: 10px;
                margin: 10px 0;
                border-radius: 5px;
                font-family: Arial, sans-serif;
                text-align: center;
            }
            button {
                padding: 10px 20px;
                margin-top: 10px;
                border: none;
                background-color: #007bff;
                color: white;
                border-radius: 5px;
                cursor: pointer;
            }
            button:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="' . $type . '-message">' . $message . '</div>
        <button onclick="window.history.back();">Go Back</button>
    </body>
    </html>';
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);
    $client_username = htmlspecialchars($_POST['client_username']);
    $code = htmlspecialchars($_POST['code']);
    $email = htmlspecialchars($_POST['email']);

    // Validate name and surname
    if (!preg_match("/^[A-Za-z]+$/", $name)) {
        showMessage("Name must contain only letters. Please try again.", "error");
    }

    if (!preg_match("/^[A-Za-z]+$/", $surname)) {
        showMessage("Surname must contain only letters. Please try again.", "error");
    }

    // Validate password
    if (!preg_match("/^(?=.*\d)[A-Za-z\d]{4,10}$/", $code)) {
        showMessage("Password must be between 4 to 10 characters long and contain at least one number. Please try again.", "error");
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        showMessage("Invalid email format. Please try again.", "error");
    }

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE client_username = :client_username OR email = :email");
        $stmt->bindParam(':client_username', $client_username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            showMessage("Username or Email already exists. Please try again.", "error");
        } else {
            // Insert new user
            $id = generateUniqueId();
            $stmt = $conn->prepare("INSERT INTO users (id, name, surname, client_username, code, email) VALUES (:id, :name, :surname, :client_username, :code, :email)");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':client_username', $client_username);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            showMessage("Registration successful!", "success");
        }
    } catch (PDOException $e) {
        showMessage("Error: " . $e->getMessage(), "error");
    }
}
?>