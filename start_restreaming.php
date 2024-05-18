<?php

// Database connection
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "stream";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Facebook Live API ka access token
$access_token = 'YOUR_FACEBOOK_ACCESS_TOKEN';

// Live session shuru hone ka notification bhejne ke liye
function sendLiveSessionNotification($conn, $access_token, $message) {
    // Database se sabhi users ke Facebook access tokens fetch karo
    $sql = "SELECT facebook_access_token FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Facebook Live API ki endpoint URL
        $url = 'https://graph.facebook.com/me/notifications';

        // Har user ke liye notification bhejo
        while($row = $result->fetch_assoc()) {
            $data = array(
                'access_token' => $access_token,
                'template' => $message,
                'href' => 'http://example.com', // Live session ka link yahaan daalo
            );

            // Facebook Live API ko POST request ke zariye hit karna
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);
        }
    }
}

// User registration ke samay Facebook access token ko database mein store karo
function registerUser($conn, $name, $email, $facebook_access_token) {
    $sql = "INSERT INTO users (name, email, facebook_access_token)
    VALUES ('$name', '$email', '$facebook_access_token')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Example usage
$name = "John Doe";
$email = "john@example.com";
$facebook_access_token = "USER_FACEBOOK_ACCESS_TOKEN";

// User registration
registerUser($conn, $name, $email, $facebook_access_token);

// Live session start notification
$message = "A live session has started on our website. Join now!";
sendLiveSessionNotification($conn, $access_token, $message);

$conn->close();
?>
