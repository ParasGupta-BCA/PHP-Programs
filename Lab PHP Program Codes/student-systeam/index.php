<?php

$file = __DIR__ . '/students.csv';

function loadStudents(): array
{
    global $file;
    $students = [];
    if (!file_exists($file)) return $students;

    $fp = fopen($file, 'r');
    while (($row = fgetcsv($fp)) !== false) {
        if (count($row) < 5) continue;
        $students[$row[0]] = [
            'erp'    => $row[0],
            'name'   => $row[1],
            'class'  => $row[2],
            'mobile' => $row[3],
            'email'  => $row[4]
        ];
    }
    fclose($fp);
    return $students;
}

function saveStudents(array $students): void
{
    global $file;
    $fp = fopen($file, 'w');
    foreach ($students as $s) {
        fputcsv($fp, [$s['erp'], $s['name'], $s['class'], $s['mobile'], $s['email']]);
    }
    fclose($fp);
}

$students = loadStudents();

$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$editStudent = null;


if ($action === 'delete' && isset($_GET['erp'])) {
    $erp = $_GET['erp'];
    unset($students[$erp]);
    saveStudents($students);
    header('Location: ?');  
    exit;
}

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $erp = trim($_POST['erp']);
    if ($erp && !isset($students[$erp])) {
        $students[$erp] = [
            'erp'    => $erp,
            'name'   => trim($_POST['name']),
            'class'  => trim($_POST['class']),
            'mobile' => trim($_POST['mobile']),
            'email'  => trim($_POST['email'])
        ];
        saveStudents($students);
    }
    header('Location: ?');
    exit;
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $erp = trim($_POST['erp']);
    if (isset($students[$erp])) {
        $students[$erp] = [
            'erp'    => $erp,
            'name'   => trim($_POST['name']),
            'class'  => trim($_POST['class']),
            'mobile' => trim($_POST['mobile']),
            'email'  => trim($_POST['email'])
        ];
        saveStudents($students);
    }
    header('Location: ?');
    exit;
}

if ($action === 'edit' && isset($_GET['erp'])) {
    $erp = $_GET['erp'];
    if (isset($students[$erp])) $editStudent = $students[$erp];
}

$search = $_GET['search'] ?? '';
if ($search) {
    $search = trim($search);
    $students = array_filter($students, function ($s) use ($search) {
        return stripos($s['erp'], $search) !== false
            || stripos($s['name'], $search) !== false;
    });
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Student Management</title>
<style>
    body{font-family:Arial,Helvetica,sans-serif;margin:2rem;background:#fafafa;}
    h1{margin-top:0;}
    .form{margin-bottom:1.5rem;}
    .form input{margin-right:0.8rem;padding:0.4rem;}
    .form button{padding:0.5rem 1rem;}
    table{width:100%;border-collapse:collapse;margin-top:1rem;}
    th,td{border:1px solid #ccc;padding:0.6rem;text-align:left;}
    th{background:#f0f0f0;}
    .action{margin-right:0.4rem;font-size:0.9rem;}
</style>
</head>
<body>
<h1>Student Management</h1>

<form class="form" method="post">
    <input type="hidden" name="action" value="add">
    <input type="text" name="erp" placeholder="ERP ID" required>
    <input type="text" name="name" placeholder="Name" required>
    <input type="text" name="class" placeholder="Class" required>
    <input type="text" name="mobile" placeholder="Mobile" required>
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Add</button>
</form>

<form class="form" method="get">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
           placeholder="Search by ERP or Name">
    <button type="submit">Search</button>
</form>

<table>
    <tr><th>ERP ID</th><th>Name</th><th>Class</th><th>Mobile</th><th>Email</th><th>Actions</th></tr>
    <?php foreach ($students as $s): ?>
    <tr>
        <td><?php echo htmlspecialchars($s['erp']); ?></td>
        <td><?php echo htmlspecialchars($s['name']); ?></td>
        <td><?php echo htmlspecialchars($s['class']); ?></td>
        <td><?php echo htmlspecialchars($s['mobile']); ?></td>
        <td><?php echo htmlspecialchars($s['email']); ?></td>
        <td>
            <a class="action" href="?action=edit&erp=<?php echo urlencode($s['erp']); ?>">Edit</a>
            <a class="action" href="?action=delete&erp=<?php echo urlencode($s['erp']); ?>"
               onclick="return confirm('Delete this student?');">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php if ($editStudent): ?>
<h2>Edit Student</h2>
<form class="form" method="post">
    <input type="hidden" name="action" value="update">
    ERP ID: <input type="text" name="erp" value="<?php echo htmlspecialchars($editStudent['erp']); ?>" readonly>
    Name: <input type="text" name="name" value="<?php echo htmlspecialchars($editStudent['name']); ?>" required>
    Class: <input type="text" name="class" value="<?php echo htmlspecialchars($editStudent['class']); ?>" required>
    Mobile: <input type="text" name="mobile" value="<?php echo htmlspecialchars($editStudent['mobile']); ?>" required>
    Email: <input type="email" name="email" value="<?php echo htmlspecialchars($editStudent['email']); ?>" required>
    <button type="submit">Update</button>
</form>
<?php endif; ?>

</body>
</html>