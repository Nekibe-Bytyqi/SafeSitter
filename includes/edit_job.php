<?php
session_start();
include('databasehandler.php');


if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'parent') {
    header('Location: ../login.html');
    exit;
}

$parent_id = $_SESSION['user_id'];


if (!isset($_GET['job_id'])) {
    echo "Error: Job ID not specified.";
    exit;
}

$job_id = $_GET['job_id'];


$query = "SELECT * FROM postajob WHERE job_id = :job_id AND contact = (SELECT email FROM parents WHERE id = :parent_id)";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
$stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
$stmt->execute();
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    echo "Error: Job not found or you don't have permission to edit this job.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
    $jobTitle = $_POST['job_title'];
    $jobType = $_POST['job_type'];
    $startDate = $_POST['start_date'];
    $location = $_POST['location'];
    $payRate = $_POST['pay_rate'];
    $jobResponsibilities = $_POST['job_responsibilities'];

    $updateQuery = "UPDATE postajob SET job_title = :job_title, job_type = :job_type, start_date = :start_date, location = :location, pay_rate = :pay_rate, job_responsibilities = :job_responsibilities WHERE job_id = :job_id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':job_title', $jobTitle, PDO::PARAM_STR);
    $updateStmt->bindParam(':job_type', $jobType, PDO::PARAM_STR);
    $updateStmt->bindParam(':start_date', $startDate, PDO::PARAM_STR);
    $updateStmt->bindParam(':location', $location, PDO::PARAM_STR);
    $updateStmt->bindParam(':pay_rate', $payRate, PDO::PARAM_STR);
    $updateStmt->bindParam(':job_responsibilities', $jobResponsibilities, PDO::PARAM_STR);
    $updateStmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        header("Location: managejobs.php");
        exit;
    } else {
        echo "Error updating job.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_job'])) {
 
    $deleteApplicationsQuery = "DELETE FROM applications WHERE job_id = :job_id";
    $deleteApplicationsStmt = $pdo->prepare($deleteApplicationsQuery);
    $deleteApplicationsStmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
    
    if ($deleteApplicationsStmt->execute()) {
       
        $deleteJobQuery = "DELETE FROM postajob WHERE job_id = :job_id";
        $deleteJobStmt = $pdo->prepare($deleteJobQuery);
        $deleteJobStmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);

        if ($deleteJobStmt->execute()) {
            header("Location: managejobs.php"); 
            exit;
        } else {
            echo "Error deleting job.";
        }
    } else {
        echo "Error deleting applications.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
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

        .form-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-container label {
            display: block;
            margin-top: 10px;
        }

        .form-container input, .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-container button {
            background-color: #ec7272;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #d06b6b;
        }

        .form-container .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .form-container .buttons button {
            width: 48%;
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
        <h1>Edit Job</h1>
    </div>

    <div class="form-container">
        <form method="post">
            <label>Job Title</label>
            <input type="text" name="job_title" value="<?php echo htmlspecialchars($job['job_title']); ?>" required>

            <label>Job Type</label>
            <input type="text" name="job_type" value="<?php echo htmlspecialchars($job['job_type']); ?>" required>

            <label>Start Date</label>
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($job['start_date']); ?>" required>

            <label>Location</label>
            <input type="text" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" required>

            <label>Pay Rate</label>
            <input type="text" name="pay_rate" value="<?php echo htmlspecialchars($job['pay_rate']); ?>" required>

            <label>Job Responsibilities</label>
            <textarea name="job_responsibilities" required><?php echo htmlspecialchars($job['job_responsibilities']); ?></textarea>

            <div class="buttons">
                <button type="submit" name="save_changes">Save Changes</button>
                <button type="submit" name="delete_job" onclick="return confirm('Are you sure you want to delete this job?');">Delete Job</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
