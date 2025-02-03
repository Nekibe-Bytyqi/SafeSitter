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


$applications = [];

try {
    
    $query = "SELECT * 
              FROM applications 
              INNER JOIN postajob ON applications.job_id = postajob.job_id 
              WHERE applications.nanny_id = :nanny_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nanny_id', $_SESSION['nanny_id'], PDO::PARAM_INT);
    $stmt->execute();
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
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
            margin-left: 200px;
            padding: 20px;
            width: 100%;
        }

        .content h1{
            margin-left:100px;
        }

        .job-list {
            margin-top: 20px;
            margin-left:100px;
        }

      
        .job-item {
    background-color: #fff;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    border: 3px double black;
    width: 60%; 
    margin: 10px auto; 
}

form {
    margin-top: 10px; 
    display: flex;
    justify-content: flex-start; 
    gap: 10px; 
}

input[type="hidden"] {
    display: none; 
}

.apply-button {
    background-color: #4CAF50;
    color: white;
    padding: 6px 12px; 
    font-size: 0.9rem; 
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.apply-button:hover {
    background-color: #45a049;
}


        .job-light {
            font-size: 0.95rem;
            color: #777;
            font-weight: normal;
        }

       
@media (max-width: 1024px) { 
    .sidebar {
        width: 200px;
    }

    .content {
        margin-left: 200px;
        width: calc(100% - 200px);
    }

    .job-item {
        width: 80%;
    }
}


@media (max-width: 480px) { 
    body {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        height: 60px; /
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
        display: flex;
        align-items: center;
    }

    .dashboard-links {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        margin-left: 0;
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
    .job-list {
        margin-left: 0;
        text-align: center;
    }

    .job-item {
        width: 100%;
    }

    form {
        flex-direction: column;
        align-items: center;
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
        <img src="../images/viewapplications.png" alt="Applications Icon" class="symbol">
        <a href="applications.php" class="symbol-text">Applications</a>
    </div>
</div>

<div class="content">
    <h1>My Applications</h1>

    <div class="job-list">
        <?php if (empty($applications)): ?>
            <p>No applications found. You haven't applied to any jobs yet.</p>
        <?php else: ?>
            <?php foreach ($applications as $job): ?>
                <div class="job-item">
                    <h3><?php echo htmlspecialchars($job['job_title']); ?></h3>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                    <p><strong>Pay Rate:</strong> <?php echo htmlspecialchars($job['pay_rate']); ?></p>
                    <p class="job-light">Job Type: <?php echo htmlspecialchars($job['job_type']); ?></p>
                    <p class="job-light">Start Date: <?php echo htmlspecialchars($job['start_date']); ?></p>
                    <p class="job-light">Responsibilities: <?php echo htmlspecialchars($job['job_responsibilities']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($job['contact']); ?></p>
                    <form action="remove.php" method="POST">
    <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['job_id']); ?>">
    <input type="hidden" name="nanny_id" value="<?php echo htmlspecialchars($_SESSION['nanny_id']); ?>">
    <button type="submit" name="remove_application" class="apply-button">Remove</button>
</form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>