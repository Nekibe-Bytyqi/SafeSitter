<?php
include('databasehandler.php');

$sql = "SELECT * FROM contact_us";
$stmt = $pdo->query($sql);

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      
        $email = json_encode($row['email']);
        $message = json_encode($row['message']);

      
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td><a href='javascript:void(0)' onclick='showMessage($email, $message)'>" . htmlspecialchars($row['email']) . "</a></td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='2'>No messages found.</td></tr>";
}
?>