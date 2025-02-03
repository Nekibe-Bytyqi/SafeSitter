<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="styleadmin.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>SaffeSitter</h2>
            <ul>
                <li><a href="adminnannies.php">Nanny Users List</a></li>
                <li><a href="adminmsg.php">Contact Us Messages</a></li>
                <li><a href="postajobsadmin.php">Posted Jobs</a></li>
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
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Country</th>
                            <th>City</th>
                            <th>Delete</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                         include('fetch_user.php'); 
                    ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</body>
</html>