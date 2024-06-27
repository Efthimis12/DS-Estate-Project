<?php
session_start(); // Start the session
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $username_email = htmlspecialchars($_GET['username_email']);
    $code = htmlspecialchars($_GET['code']);

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the user exists with either username or email and the code
        $stmt = $conn->prepare("SELECT * FROM users WHERE (client_username = :username_email OR email = :username_email) AND code = :code");
        $stmt->bindParam(':username_email', $username_email);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $stmt = $conn->prepare("REPLACE INTO is_logged_in (id, name, surname, client_username, code, email) VALUES (:id, :name, :surname, :client_username, :code, :email)");
            $stmt->bindParam(':id', $user['id']);
            $stmt->bindParam(':name', $user['name']);
            $stmt->bindParam(':surname', $user['surname']);
            $stmt->bindParam(':client_username', $user['client_username']);
            $stmt->bindParam(':code', $user['code']);
            $stmt->bindParam(':email', $user['email']);
            $stmt->execute();
        
            $_SESSION['loggedin'] = true; // Set a flag indicating the user is logged in
            
            echo '
            <style>
                .message-container {
                    text-align: center;
                    margin-top: 50px;
                }
                .welcome-message {
                    font-size: 24px;
                    color: #4CAF50;
                }
                .button {
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 20px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;
                    margin-top: 20px;
                    cursor: pointer;
                    border: none;
                    border-radius: 5px;
                }
                .button:hover {
                    background-color: #45a049;
                }
            </style>
            <div class="message-container">
                <div class="welcome-message">Welcome ' . htmlspecialchars($user['name']) . ' ' . htmlspecialchars($user['surname']) . '!</div>
                <button class="button" onclick="window.location.href = \'../../index.php\';">Close</button>
            </div>';
        } else {
            echo '
            <style>
                .message-container {
                    text-align: center;
                    margin-top: 50px;
                }
                .error-message {
                    font-size: 24px;
                    color: #f44336;
                }
                .button {
                    background-color: #f44336;
                    color: white;
                    padding: 10px 20px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;
                    margin-top: 20px;
                    cursor: pointer;
                    border: none;
                    border-radius: 5px;
                }
                .button:hover {
                    background-color: #e53935;
                }
            </style>
            <div class="message-container">
                <div class="error-message">Invalid username/email or code.</div>
                <button class="button" onclick="window.location.href = \'register_login.php\';">Return to Form</button>
            </div>';
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <script>
        // JavaScript function to navigate back to index.php
        function goToIndex() {
            window.location.href = "../../index.php";
        }
    </script>
    
</html>