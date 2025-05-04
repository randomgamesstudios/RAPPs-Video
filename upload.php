<?php
session_start();


$targetDir = "uploads/";
$dataDir = "data/";

foreach ([$targetDir, $dataDir] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0777, true);
}

$messages = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_SESSION['username'];
    $title = trim($_POST["title"] ?? '');
    $ageCategory = $_POST["ageCategory"] ?? '';

    if (!$title || !$ageCategory || !isset($_FILES['videoFile'])) {
        $messages[] = "All fields are required.";
    } else {
        $video = $_FILES["videoFile"];
        $videoExt = strtolower(pathinfo($video['name'], PATHINFO_EXTENSION));

        $allowedVideos = ['mp4', 'avi', 'mov', 'wmv', 'flv'];

        $uniqueName = md5($username . $video['name']);

        if (!in_array($videoExt, $allowedVideos)) {
            $messages[] = "Invalid video file type.";
        } elseif (file_exists($targetDir . $uniqueName . "." . $videoExt)) {
            $messages[] = "This video has already been uploaded.";
        } else {
            // Define paths
            $videoPath = $targetDir . $uniqueName . "." . $videoExt;
            $metaPath = $dataDir . $uniqueName . ".json";

            // Move the video file to the uploads directory
            move_uploaded_file($video['tmp_name'], $videoPath);

            // Save video metadata to JSON (no thumbnail)
            $metadata = [
                "username" => $username,
                "title" => $title,
                "age" => $ageCategory,
                "video" => $videoPath
            ];

            file_put_contents($metaPath, json_encode($metadata));
            $messages[] = "Upload successful!";
        }
    }
}
?>

<h2>Upload a Video</h2>

<?php foreach ($messages as $msg) echo "<p>$msg</p>"; ?>

<form method="POST" enctype="multipart/form-data">
    Title: <input type="text" name="title" required><br><br>

    Video File: <input type="file" name="videoFile" accept="video/*" required><br><br>

    Age Category:<br>
    <input type="radio" name="ageCategory" value="all" required> All Ages<br>
    <input type="radio" name="ageCategory" value="13plus" required> 13+<br><br>

    <input type="submit" value="Upload Video">
</form>

<p><a href="logout.php">Logout</a></p>
