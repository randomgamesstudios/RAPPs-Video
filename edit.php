<?php
session_start();
$id = $_GET['id'] ?? '';
$metaPath = "data/$id.json";

if (!file_exists($metaPath)) {
    die("Video not found.");
}

$data = json_decode(file_get_contents($metaPath), true);

if (!isset($_SESSION['user']) || $_SESSION['user'] !== $data['user']) {
    die("Access denied.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $ageCategory = $_POST["ageCategory"];

    if ($title && in_array($ageCategory, ['all', '13plus'])) {
        $data['title'] = $title;
        $data['age'] = $ageCategory;
        file_put_contents($metaPath, json_encode($data));
        header("Location: videos.php");
        exit;
    }
}
?>

<h2>Edit Video</h2>
<form method="POST">
    Title: <input type="text" name="title" value="<?php echo htmlspecialchars($data['title']); ?>" required><br><br>

    Age Category:<br>
    <input type="radio" name="ageCategory" value="all" <?php if ($data['age'] === 'all') echo "checked"; ?>> All Ages<br>
    <input type="radio" name="ageCategory" value="13plus" <?php if ($data['age'] === '13plus') echo "checked"; ?>> 13+<br><br>

    <input type="submit" value="Save Changes">
</form>
<p><a href="videos.php">Cancel</a></p>
