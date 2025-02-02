<?php
include('databasehandler.php');

$sql = "SELECT * FROM nannies";
$stmt = $pdo->query($sql);

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['nanny_id']) . "</td> <!-- Nanny ID -->
                <td>" . htmlspecialchars($row['first_name']) . "</td> <!-- First Name -->
                <td>" . htmlspecialchars($row['last_name']) . "</td> <!-- Last Name -->
                <td>" . htmlspecialchars($row['email']) . "</td> <!-- Email -->
                <td>" . htmlspecialchars($row['birth_date']) . "</td> <!-- Birth Date -->
                <td>" . htmlspecialchars($row['country']) . "</td> <!-- Country -->
                <td>" . htmlspecialchars($row['city']) . "</td> <!-- City -->
                <td>
                    <form action='deletenannies.php' method='POST'>
                        <input type='hidden' name='email' value='" . $row['email'] . "'> <!-- Nanny's Email -->
                        <button type='submit' onclick=\"return confirm('Are you sure you want to delete this nanny?')\">Remove</button>
                    </form>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='8'>No users found.</td></tr>"; 
}
?>