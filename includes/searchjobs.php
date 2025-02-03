<?php
session_start();
include('databasehandler.php');


if (!isset($_SESSION['nanny_id']) || $_SESSION['user_type'] !== 'nanny') {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_type'])) {
        $_SESSION['nanny_id'] = $_COOKIE['user_id']; 
        $_SESSION['user_type'] = $_COOKIE['user_type'];
    } else {
        header('Location: login.html');
        exit;
    }
}


if (!isset($_SESSION['nanny_id'])) {
    echo "<p style='color: red;'>Error: nanny_id is not set in session.</p>";
    exit; 
}

$location = '';
$jobs = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location = $_POST['location'];
    $query = "SELECT * FROM postajob WHERE location LIKE :location";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':location', "%$location%", PDO::PARAM_STR);
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


if (empty($jobs)) {
    echo "<p>No jobs found for location: " . htmlspecialchars($location) . "</p>";
}
?>

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Jobs</title>
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
    height: 100%;
    position: fixed;
}

.logo-text {
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

.content {
    margin-left: 300px;
    padding: 20px;
    width: calc(100% - 350px); 
}

.content h1 {
    width: 100%;
}

.search-form {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    max-width: 600px; 
    margin: auto; 
}

.search-form label {
    font-size: 1rem;
    white-space: nowrap; 
}

.search-form input {
    flex-grow: 1; 
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    outline: none;
    min-width: 150px; 
}

.search-form button {
    background-color: #ec7272;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
    white-space: nowrap;
    min-width: 100px;
}

.search-form button:hover {
    background-color: #d06b6b;
}



.job-list {
    margin-top: 20px;
}

.job-item {
    background-color: #fff;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 3px double black;
}

.job-light {
    font-size: 0.95rem;
    color: #777;
    font-weight: normal;
}

.apply-button {
    background-color: #4CAF50;
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.apply-button:hover {
    background-color: #45a049;
}


@media (max-width: 768px) {
    .sidebar {
        width: 100%; 
        height: auto;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-around;
        padding: 10px;
        z-index: 1000;
    }

    .logo-text {
        margin-bottom: 0;
        flex-grow: 1;
        text-align: center;
    }

    .dashboard-links {
        margin-left: 0;
        margin-bottom: 0;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 15px;
    }

    .symbol {
        width: 20px; 
    }

    .symbol-text {
        font-size: 0.9rem; 
    }

    .content {
        margin-left: 0;
        padding: 80px 15px 20px; 
        width: 100%;
    }
}

@media (max-width: 480px) {
    .sidebar {
        flex-direction: column; 
        align-items: center;
    }

    .dashboard-links {
        flex-direction: column;
        gap: 10px;
    }

    .symbol-text {
        font-size: 0.85rem;
    }

    .content {
        padding: 100px 10px 20px; 
        margin-top:120px;
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
        <a href="nannydashboard.php" class="symbol-text">Profile</a>
    </div>
    <div class="dashboard-links">
        <img src="../images/search.png" alt="Search Jobs" class="symbol">
        <a href="searchjobs.php" class="symbol-text">Search Jobs</a>
    </div>
    <div class="dashboard-links">
        <img src="../images/viewapplications.png" alt="Saved Jobs Icon" class="symbol">
        <a href="applications.php" class="symbol-text">Applications</a>
    </div>
</div>

<div class="content">
    <h1>Search for Jobs</h1>
    <form method="post" class="search-form">
        <label>Enter Location:</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" required>
        <button type="submit">Search</button>
    </form>

    <div class="job-list">
        <?php foreach ($jobs as $job): ?>
            <div class="job-item">
                <div>
                    <h3><?php echo htmlspecialchars($job['job_title']); ?></h3>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                    <p><strong>Pay Rate:</strong> <?php echo htmlspecialchars($job['pay_rate']); ?></p>
                    <p class="job-light">Job Type: <?php echo htmlspecialchars($job['job_type']); ?></p>
                    <p class="job-light">Start Date: <?php echo htmlspecialchars($job['start_date']); ?></p>
                    <p class="job-light">Responsibilities: <?php echo htmlspecialchars($job['job_responsibilities']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($job['contact']); ?></p>

           
                    <form action="apply_job.php" method="POST">
                        <input type="hidden" name="job_id" value="<?= htmlspecialchars($job['job_id']) ?>">
                        <input type="hidden" name="nanny_id" value="<?= htmlspecialchars($_SESSION['user_id'] ?? '') ?>">

                        <button type="submit" class="apply-button">Apply Now</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
