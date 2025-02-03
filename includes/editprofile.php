<?php
session_start();
include('databasehandler.php');

if (!isset($_SESSION['user_id']) && !isset($_SESSION['nanny_id']) || !isset($_SESSION['user_type'])) {
    header('Location: ../login.html');
    exit;
}

if ($_SESSION['user_type'] === 'parent') {
    $userId = $_SESSION['user_id']; 
    $table = 'parents';
    $idField = 'id';
} elseif ($_SESSION['user_type'] === 'nanny') {
    $userId = $_SESSION['nanny_id']; 
    $table = 'nannies';
    $idField = 'nanny_id';
} else {
    echo "Invalid user type.";
    exit;
}

$query = "SELECT first_name, last_name, email, country, city FROM $table WHERE $idField = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Error: User profile not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $country = $_POST['country'];
    $city = $_POST['city'];

    $updateQuery = "UPDATE $table SET first_name = :first_name, last_name = :last_name, country = :country, city = :city WHERE $idField = :id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
    $updateStmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
    $updateStmt->bindParam(':country', $country, PDO::PARAM_STR);
    $updateStmt->bindParam(':city', $city, PDO::PARAM_STR);
    $updateStmt->bindParam(':id', $userId, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
      
        setcookie('user_id', $userId, time() + (86400 * 30), "/"); 
        setcookie('user_type', $_SESSION['user_type'], time() + (86400 * 30), "/");

        if ($_SESSION['user_type'] === 'parent') {
            header("Location: parentdashboard.php");
        } elseif ($_SESSION['user_type'] === 'nanny') {
            header("Location: nannydashboard.php");
        }
        exit;
    } else {
        echo "Error updating profile.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_profile'])) {
    $deleteQuery = "DELETE FROM $table WHERE $idField = :id";
    $deleteStmt = $pdo->prepare($deleteQuery);
    $deleteStmt->bindParam(':id', $userId, PDO::PARAM_INT);

    if ($deleteStmt->execute()) {
        session_destroy();

      
        setcookie('user_id', '', time() - 3600, "/"); 
        setcookie('user_type', '', time() - 3600, "/"); 

        header("Location: ../login.html");
        exit;
    } else {
        echo "Error deleting profile.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
       

       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    height: 100vh;
    background-color: white;
}

.navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: rgba(171, 168, 168, 0.56);
    color: white;
    padding: 10px;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.logo-text {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #ec7272;
}

.logo {
    width: 50px;
    height: auto;
    margin-right: 10px;
}

.container {
    width: 50%;
    margin: auto;
    padding-top: 70px;
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-top: 100px;
}

h2 {
    text-align: center;
}

label {
    display: block;
    font-weight: bold;
    margin-top: 10px;
}

input {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

input:disabled {
    background-color: #ddd;
    cursor: not-allowed;
}

.buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.buttons button {
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

#saveBtn {
    background-color: #2ecc71;
    color: white;
}

#deleteBtn {
    background-color: #e74c3c;
    color: white;
}

button:hover {
    opacity: 0.8;
}


@media (max-width: 768px) {
    .container {
        width: 80%;
        padding: 15px;
        margin-top: 60px;
    }

    .buttons {
        flex-direction: column;
        align-items: center;
    }

    .buttons button {
        width: 100%;
        margin-bottom: 10px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .container {
        width: 90%;
        margin-top: 50px;
        padding: 10px;
    }

    .navbar {
        padding: 8px;
        flex-direction: column;
        align-items: flex-start;
        justify-content: flex-start;
    }

    .logo {
        width: 40px;
    }

    .buttons button {
        font-size: 14px;
        padding: 8px 12px;
    }

    input {
        font-size: 14px;
        padding: 8px;
    }
}

    </style>
</head>
<body>

<header class="navbar">
    <div class="logo-text">
        <img src="../logo.png" alt="Logo" class="logo">
        <span class="text">SafeSitter</span>
    </div>
</header>

<div class="container">
    <h2>Edit Profile</h2>
    
    <form method="post">
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>

        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>

        <label>Email:</label>
        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>

        <label>Country:</label>
        <input type="text" name="country" value="<?php echo htmlspecialchars($user['country']); ?>">

        <label>City:</label>
        <input type="text" name="city" value="<?php echo htmlspecialchars($user['city']); ?>">

        <div class="buttons">
            <button type="submit" id="saveBtn" name="save_changes">Save Changes</button>
            <button type="submit" id="deleteBtn" name="delete_profile" onclick="return confirm('Are you sure you want to delete your profile?');">Delete Profile</button>
        </div>
    </form>
</div>

</body>
</html>
