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
            max-width: 600px;
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
        .user-data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        .user-data-table th, .user-data-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .user-data-table th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
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

        // Check if form data is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get user input
            $user = $_POST['username'];
            $pass = $_POST['password'];

            // Secure SQL query using prepared statements
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
            $stmt->bind_param("ss", $user, $pass);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<div class='result-title'>Login successful</div>";
                echo "<div class='result-message'>User Data:</div>";
                echo "<table class='user-data-table'>";
                echo "<tr><th>ID</th><th>Username</th><th>Password</th></tr>";

                // Display user data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["username"] . "</td>";
                    echo "<td>" . $row["password"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";

                // Fetch and display all users' data
                $allUsersResult = $conn->query("SELECT * FROM users");
                if ($allUsersResult->num_rows > 0) {
                    echo "<div class='result-message'>All Users Data:</div>";
                    echo "<table class='user-data-table'>";
                    echo "<tr><th>ID</th><th>Username</th><th>Password</th></tr>";
                    while ($row = $allUsersResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["username"] . "</td>";
                        echo "<td>" . $row["password"] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            } else {
                echo "<div class='result-title'>Invalid credentials or no data found</div>";
            }

            $stmt->close();
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