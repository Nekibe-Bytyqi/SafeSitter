<?php
include('databasehandler.php');


$sql = "SELECT * FROM parents"; 
$stmt = $pdo->query($sql); 


if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['first_name']) . "</td>
                <td>" . htmlspecialchars($row['last_name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['country']) . "</td>
                <td>" . htmlspecialchars($row['city']) . "</td>
                <td>
                    <form action='delete_user.php' method='POST'>
                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                        <button type='submit' onclick=\"return confirm('Are you sure you want to delete this user?')\">Remove</button>
                    </form>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No users found.</td></tr>";
}
?>