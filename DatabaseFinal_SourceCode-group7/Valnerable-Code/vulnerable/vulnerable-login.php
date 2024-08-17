<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Result</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #e3f2fd;
            font-family: 'Arial', sans-serif;
            margin: 0;
        }
        .result-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .result-title {
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            color: #007bff;
        }
        .result-message {
            font-size: 1rem;
            color: #333;
            margin-bottom: 1rem;
        }
        .user-data {
            font-size: 1rem;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="result-container">
        <?php
        $servername = "localhost";
        $username = "root"; // replace with your MySQL username
        $password = "Ar@200703"; // replace with your MySQL password
        $dbname = "sql_injection_demo";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("<div class='result-message'>Connection failed: " . $conn->connect_error . "</div>");
        }

        // Get user input
        $user = $_POST['username'];
        $pass = $_POST['password'];

        // Function to check for true condition patterns
        function isTrueCondition($str) {
            $parts = explode('=', $str);
            return count($parts) == 2 && $parts[0] === $parts[1];
        }

        // Check if the password contains any true conditions
        $isConditionTrue = false;
        $passConditions = explode(' ', $pass);
        foreach ($passConditions as $condition) {
            if (isTrueCondition($condition)) {
                $isConditionTrue = true;
                break;
            }
        }

        // Vulnerable SQL query
        if ($isConditionTrue) {
            $sql = "SELECT * FROM users";
        } else {
            $sql = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<div class='result-title'>Login successful</div>";
            echo "<div class='result-message'>User Data:</div>";
            while ($user_data = $result->fetch_assoc()) {
                echo "<div class='user-data'>Username: " . $user_data['username'] . "</div>";
                echo "<div class='user-data'>Password: " . $user_data['password'] . "</div>";
           
            }
        } else {
            echo "<div class='result-title'>Invalid credentials</div>";
        }

        $conn->close();
        ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>