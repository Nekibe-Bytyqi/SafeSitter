<?php
include('databasehandler.php');


$sql = "SELECT * FROM postajob"; 
$stmt = $pdo->query($sql); 


if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['job_id']) . "</td>
                <td>" . htmlspecialchars($row['job_title']) . "</td>
                <td>" . htmlspecialchars($row['job_type']) . "</td>
                <td>" . htmlspecialchars($row['start_date']) . "</td>
                <td>" . htmlspecialchars($row['location']) . "</td>
                <td>" . htmlspecialchars($row['pay_rate']) . "</td>
                <td>" . htmlspecialchars($row['job_responsibilities']) . "</td>
                <td>" . htmlspecialchars($row['contact']) . "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No users found.</td></tr>";
}
?>