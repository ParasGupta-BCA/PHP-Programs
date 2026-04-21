<?php
$errors = [];          
$success = '';         

$first_name  = $_POST['first_name']  ?? '';
$last_name   = $_POST['last_name']   ?? '';
$class       = $_POST['class']       ?? '';
$section     = $_POST['section']     ?? '';
$mobile      = $_POST['mobile']      ?? '';

$first_name = trim($first_name);
$last_name  = trim($last_name);
$class      = trim($class);
$section    = trim($section);
$mobile     = trim($mobile);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($first_name === '')  $errors[] = 'First name required.';
    if ($last_name === '')   $errors[] = 'Last name required.';
    if ($class === '')       $errors[] = 'Select a class.';
    if ($section === '')     $errors[] = 'Select a section.';
    if ($mobile === '' || !preg_match('/^\d{10,15}$/', $mobile))
        $errors[] = 'Invalid phone (10‑15 digits).';

    if (empty($errors)) {
        $first_name = htmlspecialchars($first_name);
        $last_name  = htmlspecialchars($last_name);
        $success = "✅ Registered: $first_name $last_name (Class: $class, Section: $section, Phone: $mobile)";
    }
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8">
<title>Registration</title>

<style>
  body{font:1rem Arial;margin:2rem;background:#fafafa}
  h1{color:#333}
  form{background:#fff;padding:.5rem;border:1px solid #ddd;margin:.5rem 0}
  label{display:block;margin:.25rem 0}
  input,select{width:100%;padding:.3rem;border:1px solid #aaa;border-radius:3px}
  .error{background:#fee;color:#900}
  .success{background:#e6ffe6;color:#080}
</style>
</head>

<body>
<h1 style="text-align:center">Student Registration</h1><br>

<?php if ($errors): ?>
  <div class="error"><ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul></div>
<?php endif; ?>

<?php if ($success): ?>
  <div class="success"><?php echo $success; ?></div>
<?php endif; ?>

<h2 style="margin-top:1rem;">Register – GET</h2>
<form method="get">
  <label>First name:<input name="first_name" required></label>
  <label>Last name:<input name="last_name" required></label>
  <label>Class:<select name="class" required>
      <option value="">--</option>
      <option value="1">BCA</option>
      <option value="2">MBA</option>
      <option value="3">BBA</option>
      <option value="4">MCA</option>
  </select></label>
  <label>Section:<select name="section" required>
      <option value="">--</option>
      <option value="A">Morning</option>
      <option value="B">Evening</option>
      <option value="C">Afternoon</option>
  </select></label>
  <label>Mobile:<input type="tel" name="mobile" pattern="\d{10,15}" required></label>
  <button type="submit">Submit (GET)</button>
</form>

<h2 style="margin-top:1rem;">Register – POST</h2>
<form method="post">
  <label>First name:<input name="first_name" required></label>
  <label>Last name:<input name="last_name" required></label>
  <label>Class:<select name="class" required>
      <option value="">--</option>
      <option value="1">BCA</option>
      <option value="2">MBA</option>
      <option value="3">BBA</option>
      <option value="4">MCA</option>
  </select></label>
  <label>Section:<select name="section" required>
      <option value="">--</option>
      <option value="A">Morning</option>
      <option value="B">Evening</option>
      <option value="C">Afternoon</option>
  </select></label>
  <label>Mobile:<input type="tel" name="mobile" pattern="\d{10,15}" required></label>
  <button type="submit">Submit (POST)</button>
</form>
</body></html>
