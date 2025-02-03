<?php
session_start();
include('databasehandler.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'parent') {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_type'])) {
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['user_type'] = $_COOKIE['user_type'];
    } else {
        header('Location: ../login.html');
        exit;
    }
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


$query = "SELECT * FROM postajob WHERE contact = :contact";  
$stmt = $pdo->prepare($query);
$stmt->bindParam(':contact', $parent['email'], PDO::PARAM_STR);  
$stmt->execute();
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($jobs)) {
    echo "No job posted yet.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
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
    flex-grow: 1;
    padding: 20px;
    margin-left: 300px;
}

.header {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
}

.header h1 {
    font-size: 2rem;
    margin: 0;
    color: #333;
}

.job-card {
    background-color: white;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #ddd;
    max-width: 600px;
    margin-left: 0px;
}

.job-card h2 {
    margin-top: 0;
    color: #333;
}

.job-card p {
    font-size: 1rem;
    color: #555;
    margin: 5px 0;
}

.job-card form {
    display: inline-block;
    margin-right: 10px;
}

.job-card button {
    background-color: #ec7272;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s;
}

.job-card button:hover {
    background-color: #d06b6b;
}


@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        padding: 15px;
        box-sizing: border-box;
    }

    .content {
        margin-left: 0;
    }

    .header h1 {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        padding: 15px;
        box-sizing: border-box;
    }

    .content {
        margin-left: 0;
        padding-top: 80px; 
    }

    .dashboard-links {
        margin-left: 0;
    }

    .header h1 {
        font-size: 1.2rem;
    }

    .job-card {
        width: 100%;
        margin-left: 0;
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
        <h1>Your Posted Jobs</h1>
    </div>

    <?php foreach ($jobs as $job): ?>
        <div class="job-card">
            <h2><?php echo htmlspecialchars($job['job_title']); ?></h2>
            <p><strong>Job Type:</strong> <?php echo htmlspecialchars($job['job_type']); ?></p>
            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($job['start_date']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
            <p><strong>Pay Rate:</strong> <?php echo htmlspecialchars($job['pay_rate']); ?></p>
            <p><strong>Responsibilities:</strong> <?php echo htmlspecialchars($job['job_responsibilities']); ?></p>

            <form action="edit_job.php" method="get">
                <button type="submit" name="job_id" value="<?php echo $job['job_id']; ?>">Edit</button>
            </form>
            
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>



