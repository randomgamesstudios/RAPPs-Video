<?php
session_start();
$metaFiles = glob("data/*.json");

echo "<h2>Welcome to RAPPs Video!</h2>";
echo "<p><a href='upload.php'>Upload</a> | <a href='logout.php'>Logout</a></p>";

if (count($metaFiles) === 0) {
    echo "<p>No videos uploaded yet.</p>";
} else {
    foreach ($metaFiles as $meta) {
        $data = json_decode(file_get_contents($meta), true);
        $id = basename($meta, ".json");

        echo "<div style='margin-bottom:30px'>";
        echo "<strong>" . htmlspecialchars($data['title']) . "</strong><br>";
        echo "Uploaded by: " . htmlspecialchars($data['username']) . "<br>";
        echo "Age: " . ($data['age'] === "13plus" ? "13+" : "All Ages") . "<br>";
        echo "<video width='320' controls>
                <source src='" . $data['video'] . "' type='video/mp4'>
              </video><br>";

        // Show edit/delete only to owner
        if (isset($_SESSION['username']) && $_SESSION['username'] === $data['username']) {
            echo "<a href='edit.php?id=$id'>Edit</a> | ";
            echo "<a href='delete.php?id=$id' onclick=\"return confirm('Are you sure?')\">Delete</a>";
        }

        echo "</div><hr>";
    }
}
?>
