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

$parent_email = $parent['email'];

$query = "SELECT job_id, job_title FROM postajob WHERE contact = :contact";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':contact', $parent_email, PDO::PARAM_STR);
$stmt->execute();
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$job_applicants = [];

foreach ($jobs as $job) {
    $job_id = $job['job_id'];

    $query = "SELECT nannies.first_name, nannies.last_name, nannies.email, nannies.country, nannies.city, nannies.birth_date
              FROM applications
              INNER JOIN nannies ON applications.nanny_id = nannies.nanny_id
              WHERE applications.job_id = :job_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
    $stmt->execute();
    $applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($applicants) {
        $job_applicants[$job_id] = [
            'job_title' => $job['job_title'],
            'applicants' => $applicants
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants</title>
    <style>
      
      body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: Arial, sans-serif;
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

.job-list {
    margin-left: 50px;
}

.job-list h2 {
    font-size: 1.5rem;
    color: #333;
}

.applicant-list {
    list-style-type: none;
    padding: 0;
}

.applicant-list li {
    background-color: #f9f9f9;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 10px;
    border: 3px solid black;
}

.applicant-list li strong {
    color: #444;
}

.applicant-list li p {
    margin: 5px 0;
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
        <h1>View Applicants</h1>
    </div>
       

    
    <?php
    if (empty($job_applicants)) {
        echo "<p>No applicants yet for your jobs.</p>";
    } else {
        foreach ($job_applicants as $job_id => $job_data) {
            echo "<div class='job-list'>";
            echo "<h2>Job: " . htmlspecialchars($job_data['job_title']) . "</h2>";
            if (empty($job_data['applicants'])) {
                echo "<p>No applicants yet for this job.</p>";
            } else {
                echo "<ul class='applicant-list'>";
                foreach ($job_data['applicants'] as $applicant) {
                    echo "<li>";
                    echo "<strong>Name:</strong> " . htmlspecialchars($applicant['first_name']) . " " . htmlspecialchars($applicant['last_name']) . "<br>";
                    echo "<strong>Email:</strong> " . htmlspecialchars($applicant['email']) . "<br>";
                    echo "<strong>Country:</strong> " . htmlspecialchars($applicant['country']) . "<br>";
                    echo "<strong>City:</strong> " . htmlspecialchars($applicant['city']) . "<br>";
                    echo "<strong>Birth Date:</strong> " . htmlspecialchars($applicant['birth_date']) . "<br>";
                    echo "</li>";
                }
                echo "</ul>";
            }
            echo "</div>";
        }
    }
    ?>

</div>

</body>
</html>

