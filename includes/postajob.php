<?php
session_start();
include('databasehandler.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'parent') {
    header('Location: ../login.html');
    exit;
}

$parent_id = $_SESSION['user_id'];


$query = "SELECT email FROM parents WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $parent_id, PDO::PARAM_INT);
$stmt->execute();
$parent = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$parent) {
    echo "Error: Parent profile not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_title = $_POST['job_title'];
    $job_type = $_POST['job_type'];
    $start_date = $_POST['start_date'];
    $location = $_POST['location'];
    $pay_rate = $_POST['pay_rate'];
    $job_responsibilities = $_POST['job_responsibilities'];
    $contact = $parent['email']; 

    
    $insert_query = "INSERT INTO postajob (job_title, job_type, start_date, location, pay_rate, job_responsibilities, contact) 
                     VALUES (:job_title, :job_type, :start_date, :location, :pay_rate, :job_responsibilities, :contact)";
    $insert_stmt = $pdo->prepare($insert_query);
    $insert_stmt->bindParam(':job_title', $job_title, PDO::PARAM_STR);
    $insert_stmt->bindParam(':job_type', $job_type, PDO::PARAM_STR);
    $insert_stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
    $insert_stmt->bindParam(':location', $location, PDO::PARAM_STR);
    $insert_stmt->bindParam(':pay_rate', $pay_rate, PDO::PARAM_STR);
    $insert_stmt->bindParam(':job_responsibilities', $job_responsibilities, PDO::PARAM_STR);
    $insert_stmt->bindParam(':contact', $contact, PDO::PARAM_STR);
    $insert_stmt->execute();

 
    header("Location: managejobs.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job</title>
    <style>
       
       body, html {
    margin: 0;
    padding: 0;
    height: 100%;
}


.sidebar {
    width: 250px;
    background-color: #d3d3d3;
    color: white;
    position: fixed;
    height: 100%;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.sidebar .logo-text {
    display: flex;
    align-items: center;
    margin-bottom: 40px;
}

.logo {
    width: 40px;
    height: auto;
    margin-right: 10px;
}

.text {
    font-size: 1.5rem;
    color: #ec7272;
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
    color: black;
    text-decoration: none;
    transition: color 0.3s;
}

.symbol-text:hover {
    color: #d6d6d6;
}


.content {
    margin-left: 250px;
    padding: 20px 40px;
    height: 100%;
    overflow-y: auto;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header h1 {
    font-size: 2rem;
    margin: 0;
    color: #444;
    margin-left: 50px;
}


.profile-form {
    background-color: #f9f9f9;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-left: 40px;
}

.profile-form label {
    display: block;
    font-size: 1.1rem;
    margin-bottom: 5px;
    margin-top: 10px;
}

.profile-form input, 
.profile-form textarea {
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


@media (max-width: 768px) { 
    .sidebar {
        width: 180px;
        padding: 10px;
    }

    .content {
        margin-left: 180px;
        padding: 15px;
    }

    .header h1 {
        font-size: 1.6rem;
        margin-left: 15px;
    }

    .profile-form {
        padding: 20px;
        margin-left: 10px;
    }
}

@media (max-width: 480px) { 
    
    .sidebar {
        width: 100%;
        height: auto; 
        padding: 10px;
        position: relative; 
    }

    .content {
        margin-left: 0; 
        padding: 15px;
    }

    .profile-form {
        padding: 15px;
        margin-left: 0;
    }

    .header h1 {
        font-size: 1.5rem;
        margin-left: 10px;
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
        <img src="../images/managejobs.png" alt="Manage Jobs" class="symbol">
        <a href="managejobs.php" class="symbol-text">Manage Jobs</a>
    </div>
    <div class="dashboard-links">
        <img src="../images/viewapplications.png" alt="View Applicants" class="symbol">
        <a href="view_applicants.php" class="symbol-text">View Applicants</a>
    </div>
</div>

<div class="content">
    <div class="header">
        <h1>Post a Job</h1>
    </div>

   
    <div class="profile-form">
        <form method="post">
            <label>Job Title:</label>
            <input type="text" name="job_title" required>

            <label>Job Type:</label>
            <input type="text" name="job_type" required>

            <label>Start Date:</label>
            <input type="date" name="start_date" required>

            <label>Location:</label>
            <input type="text" name="location" required>

            <label>Pay Rate:</label>
            <input type="text" name="pay_rate" required>

            <label>Job Responsibilities:</label>
            <textarea name="job_responsibilities" rows="4" required></textarea>

  
            <input type="hidden" name="contact" value="<?php echo htmlspecialchars($parent['email']); ?>">

            <button type="submit">Post Job</button>
        </form>
    </div>
</div>

</body>
</html>

