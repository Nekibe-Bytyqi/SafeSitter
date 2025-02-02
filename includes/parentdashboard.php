<?php
session_start();
include('databasehandler.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'parent') {
    header('Location: ../login.html');
    exit;
}

$parent_id = $_SESSION['user_id'];

$query = "SELECT first_name, last_name, email, country, city FROM parents WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $parent_id, PDO::PARAM_INT);
$stmt->execute();
$parent = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$parent) {
    echo "Error: Parent profile not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $country = $_POST['country'];
    $city = $_POST['city'];

    $update_query = "UPDATE parents SET first_name = :first_name, last_name = :last_name, country = :country, city = :city WHERE id = :id";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $update_stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $update_stmt->bindParam(':country', $country, PDO::PARAM_STR);
    $update_stmt->bindParam(':city', $city, PDO::PARAM_STR);
    $update_stmt->bindParam(':id', $parent_id, PDO::PARAM_INT);
    $update_stmt->execute();

    header("Location: parentdashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Dashboard</title>
    <style>
     
     
     body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    height: 100vh;
    background-color: white;
}

.sidebar {
    width: 250px;
    background-color: #d3d3d3;
    color: white;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.sidebar .logo-text {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.logo {
    width: 35px;
    height: auto;
    margin-right: 8px;
}

.text {
    font-size: 1.3rem;
    color: #ec7272;
    font-weight: bold;
}

.dashboard-links {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.symbol {
    width: 22px;
    height: auto;
    margin-right: 8px;
}

.symbol-text {
    font-size: 1rem;
    color: black;
    text-decoration: none;
    transition: color 0.3s;
}

.symbol-text:hover {
    color: #d6d6d6;
}

.content {
    flex-grow: 1;
    padding: 20px 30px;
    overflow-y: auto;
    position: relative;
    max-width: 800px;
    margin: auto;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.header h1 {
    font-size: 1.8rem;
    margin: 0;
    color: #444;
}

.user-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.sign-out {
    position: fixed;
    top: 15px;
    right: 15px;
    width: 20px;
    height: auto;
    cursor: pointer;
}

.profile-form {
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 6px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.profile-form label {
    font-size: 1rem;
    margin-bottom: 5px;
    margin-top: 8px;
}

.profile-form input {
    width: 100%;
    padding: 8px;
    margin-bottom: 12px;
    font-size: 0.9rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.profile-form button {
    background-color: #ec7272;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.profile-form button:hover {
    background-color: #d06b6b;
}

@media (max-width: 768px) {
    .sidebar {
        width: 200px;
        padding: 15px;
    }

    .content {
        padding: 15px;
    }
}

@media (max-width: 480px) {
    body {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        height: auto;
        padding: 10px;
        flex-direction: row;
        justify-content: space-around;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    .sidebar .logo-text {
        margin-bottom: 0;
    }

    .dashboard-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
    }

    .content {
        padding: 70px 15px 15px;
        max-width: 100%;
    }

    .header h1 {
        font-size: 1.5rem;
    }

    .profile-form {
        padding: 10px;
    }

    .profile-form input {
        font-size: 0.85rem;
    }

    .profile-form button {
        font-size: 0.85rem;
        padding: 6px 12px;
    }
}



    </style>
</head>
<body>


<div class="sidebar">
    <div class="logo-text">
    <img src="../logo.png" alt="Logo" class="logo">
        <h2 class="text">SafeSitter</h2>
    </div>

    <div class="dashboard-links">
        <img src="../images/profilee.png" alt="Profile" class="symbol">
        <a href="parentdashboard.php" class="symbol-text">Profile</a>
    </div>
    <div class="dashboard-links">
        <img src="../images/postajob.png" alt="Post a Job" class="symbol">
        <a href="postajob.php" class="symbol-text">Post a Job</a>
    </div>
    <div class="dashboard-links">
        <img src="../images/managejobs.png" alt="Manage jobs" class="symbol">
        <a href="managejobs.php" class="symbol-text">Manage jobs</a>
    </div>
    <div class="dashboard-links">
        <img src="../images/viewapplications.png" alt="View Applications" class="symbol">
        <a href="view_applicants.php" class="symbol-text">View Applicants</a>
    </div>
</div>


<div class="content">
    <div class="header">
        <h1>Welcome to Your Profile</h1>
        <div class="user-actions">
           <a href="../index.html"> <img src="../images/signout.png" alt="Sign Out" class="sign-out"></a>
        </div>
    </div>


    <div class="profile-form">
        
    <form method="post" action="editprofile.php">
    <label>First Name:</label>
    <input type="text" name="first_name" value="<?php echo htmlspecialchars($parent['first_name']); ?>" required>
    
    <label>Last Name:</label>
    <input type="text" name="last_name" value="<?php echo htmlspecialchars($parent['last_name']); ?>" required>
    
    <label>Email:</label>
    <input type="email" value="<?php echo htmlspecialchars($parent['email']); ?>" disabled>
    
    <label>Country:</label>
    <input type="text" name="country" value="<?php echo htmlspecialchars($parent['country']); ?>">
    
    <label>City:</label>
    <input type="text" name="city" value="<?php echo htmlspecialchars($parent['city']); ?>">

    <button type="submit" name="edit_profile">Edit Profile</button>
</form>


    </div>
</div>

</body>
</html>

