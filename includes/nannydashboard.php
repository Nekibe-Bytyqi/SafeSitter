<?php
session_start();
include('databasehandler.php');


if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'nanny') {
    header('Location: ../login.html');
    exit;
}

$nanny_id = $_SESSION['nanny_id'];



$query = "SELECT first_name, last_name, email, country, city, birth_date FROM nannies WHERE nanny_id = :nanny_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':nanny_id', $nanny_id, PDO::PARAM_INT);
$stmt->execute();
$nanny = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$nanny) {
    echo "Error: Nanny profile not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $country = $_POST['country'];
    $city = $_POST['city'];

    $update_query = "UPDATE nannies SET first_name = :first_name, last_name = :last_name, country = :country, city = :city WHERE nanny_id = :nanny_id";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $update_stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $update_stmt->bindParam(':country', $country, PDO::PARAM_STR);
    $update_stmt->bindParam(':city', $city, PDO::PARAM_STR);
    $update_stmt->bindParam(':nanny_id', $nanny_id, PDO::PARAM_INT);
    $update_stmt->execute();

    header("Location: nannydashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nanny Profile</title>
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
    background-color: #757b88;
    color: white;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100vh; 
    flex-shrink: 0; 
    position: fixed; 
    left: 0; 
}

        .sidebar .logo-text {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 40px;
            height: auto;
            margin-right: 10px;
        }

        .text {
            font-size: 1.5rem;
            color: #ffffff;
            font-weight: bold;
        }

        .dashboard-links {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            margin-left: 20px;
            
        }

        .symbol {
            width: 24px;
            height: auto;
            margin-right: 10px;
        }

        .symbol-text {
            font-size: 1.1rem;
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .symbol-text:hover {
            color: #d6d6d6;
        }

        .active{
            color: #4CAF50;
        }

        .content {
    flex-grow: 1;
    padding: 20px 40px;
    overflow-y: auto;
    position: relative;
    max-width: 800px;
    margin: auto;
    margin-left: 350px; 
}
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    position: relative; 
}

        .header h1 {
            font-size: 2rem;
            margin: 0;
            color: #444;
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
            
        .sign-out {
    position: fixed;
    top: 20px; 
    right: 20px; 
    width: 24px;
    height: auto;
    cursor: pointer;
}

       
        .profile-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .profile-form label {
            display: block;
            font-size: 1.1rem;
            margin-bottom: 5px;
            margin-top: 10px;
        }

        .profile-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .profile-form button {
            background-color: #ec7272;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .profile-form button:hover {
            background-color: #d06b6b;
        }

      
@media (max-width: 1024px) {
    .sidebar {
        width: 200px;
    }

    .content {
        margin-left: 220px;
    }
}

@media (max-width: 480px) { 
    body {
        flex-direction: column;
    }

    
    .sidebar {
        width: 100%;
        height: 60px; 
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        z-index: 1000;
    }

    
    .sidebar .logo-text {
        display: flex; 
        align-items: center;
    }

    .logo {
        width: 30px; 
        height: auto;
    }

    
    .dashboard-links {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        margin: 0;
    }

    .symbol {
        width: 20px;
        margin-right: 5px;
    }

    .symbol-text {
        font-size: 0.9rem;
    }

    
    .content {
        margin-left: 0;
        width: 100%;
        padding: 10px;
        margin-top: 70px; 
    }

   
    .content h1,
    .profile-form {
        margin-left: 0;
        text-align: center;
    }

    .profile-form input {
        width: 100%;
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
        <img src="../images/profilee.png" alt="Profile Icon" class="symbol">
       <span class="active"><a href="nannydashboard.php" class="symbol-text">Profile</a></span> 
    </div>
    <div class="dashboard-links">
        <img src="../images/search.png" alt="Search Jobs Icon" class="symbol">
        <a href="searchjobs.php" class="symbol-text">Search Jobs</a>
    </div>
    <div class="dashboard-links">
        <img src="../images/viewapplications.png" alt="Saved Jobs Icon" class="symbol">
        <a href="applications.php" class="symbol-text">Applications</a>
    </div>
</div>


<div class="content">
    <div class="header">
        <h1>Welcome to Your Profile</h1>
        <div class="user-actions">
            <a href="../index.html"><img src="../images/signout.png" alt="Sign Out" class="sign-out"></a>
        </div>
    </div>

 
    <div class="profile-form">
        <form method="post" action="editprofile.php">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($nanny['first_name']); ?>" required>
            
            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($nanny['last_name']); ?>" required>
            
            <label>Email:</label>
            <input type="email" value="<?php echo htmlspecialchars($nanny['email']); ?>" disabled>
            
            <label>Country:</label>
            <input type="text" name="country" value="<?php echo htmlspecialchars($nanny['country']); ?>">
            
            <label>City:</label>
            <input type="text" name="city" value="<?php echo htmlspecialchars($nanny['city']); ?>">
            
            <label>Birth Date:</label>
            <input type="date" name="birth_date" value="<?php echo htmlspecialchars($nanny['birth_date']); ?>">
            
            <a href="editprofile.php" ><button type="submit">Edit Profile</button></a>
            
        </form>
    </div>
</div>

</body>
</html>