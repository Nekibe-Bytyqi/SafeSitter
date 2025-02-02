<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styleadmin.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>SaffeSitter</h2>
            <ul>
                <li><a href="admin.php">Parent Users List</a></li>
                <li><a href="nannies.php">Nanny Users List</a></li>
                <li><a href="adminsmsg.php">Contact Us Messages</a></li>
                <button><a href="index.html">Log Out</a></button>
            </ul>
        </div>

        <div class="main-content">
            <header>
                <h1>Admin Dashboard</h1>
            </header>

            <section class="user-list">
                <h2>Parent Users List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Job Title</th>
                            <th>Job Type</th>
                            <th>Start Date</th>
                            <th>Location</th>
                            <th>Pay Rate</th>
                            <th>Responsibilities</th>
                            <th>Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        
                        include('fetch_jobs.php');
                    ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</body>
</html>