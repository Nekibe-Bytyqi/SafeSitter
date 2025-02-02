<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styleadmin.css">
    <style>
#messageModal {
    display: none; /
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7); 
    justify-content: center;
    align-items: center;
    z-index: 999; 
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 10px; 
    width: 80%; 
    max-width: 600px; 
    margin: auto;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    position: relative; 
}
.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 18px;
    font-weight: bold;
    color: #333; 
    cursor: pointer;
}
.close-btn:hover {
    color: #d9534f; 
}
.modal-content h3 {
    margin-top: 0;
    font-size: 24px;
    font-weight: 600;
    color: #333;
}
#messageDetails {
    margin-top: 15px;
    font-size: 16px;
    line-height: 1.5;
    color: #555;
}
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>SafeSitter</h2>
            <ul>
                <li><a href="admin.php">Parent Users List</a></li>
                <li><a href="nannies.php">Nanny Users List</a></li>
                <li><a href="postajobsadmin.php">Posted Jobs</a></li>
                <button><a href="index.html">Log Out</a></button>
            </ul>
        </div>

        <div class="main-content">
            <header>
                <h1>Admin Dashboard</h1>
            </header>

            <section class="user-list">
                <h2>Contact Us Messages</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Email</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('fetch_msg.php');
                        ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>

    <div id="messageModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">X</span>
        <h3>Message Details</h3>
        <div id="messageDetails"></div>
    </div>
</div>

    <script>
      function showMessage(email, message) {
    const modal = document.getElementById('messageModal');
    const messageDetails = document.getElementById('messageDetails');
    
    messageDetails.innerHTML = `
        <p><strong>Email:</strong> ${email}</p>
        <p><strong>Message:</strong> ${message}</p>
    `;
  
    modal.style.display = "flex";
}

function closeModal() {
    document.getElementById('messageModal').style.display = "none";
}
    </script>
</body>
</html>