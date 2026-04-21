<?php
# Write a php code to make personalized feel user set a cookies with brower theam,color, language
# when a visters vist with a number of times on that website.

$visits = isset($_COOKIE['visits'])
          ? intval($_COOKIE['visits']) + 1
          : 1;
setcookie('visits',$visits,time()+365*24*60*60,'/');

$theme = $_POST['theme'] ?? $_COOKIE['theme'] ?? 'light';
$color = $_POST['color'] ?? $_COOKIE['color'] ?? '3498db';
$lang  = $_POST['lang']  ?? $_COOKIE['lang']  ?? 'en';

if ($_SERVER['REQUEST_METHOD']=='POST') {
    setcookie('theme',$theme,time()+365*24*60*60,'/');
    setcookie('color',$color,time()+365*24*60*60,'/');
    setcookie('lang',$lang,time()+365*24*60*60,'/');
    header('Location:'.$_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
<meta charset="utf-8">
<title>Cookie Demo</title>
<style>
body{font-family:sans-serif;margin:2rem;}
body.light{color:#2c3e50;}
body.dark{color:#ecf0f1;}
.color{display:inline-block;width:20px;height:20px;margin-right:.5rem;}
</style>
</head>
<body class="<?php echo $theme; ?>" style="background:#<?php echo $color; ?>;">
<h1>Hello!</h1>
<p>Visits: <strong><?php echo $visits; ?></strong></p>
<p>Theme: <?php echo $theme; ?> |
   Color: <span class="color" style="background:#<?php echo $color; ?>;"></span>#<?php echo $color; ?> |
   Lang: <?php echo $lang; ?></p>

<form method="post">
  <label>Theme:
    <select name="theme">
      <option value="light" <?php if($theme=='light')echo'selected'; ?>>Light</option>
      <option value="dark"  <?php if($theme=='dark')echo'selected'; ?>>Dark</option>
    </select>
  </label><br><br>

  <label>Color:
    <input type="color" name="color" value="#<?php echo $color; ?>">
  </label><br><br>

  <label>Lang:
    <select name="lang">
      <option value="en" <?php if($lang=='en')echo'selected'; ?>>EN</option>
      <option value="es" <?php if($lang=='es')echo'selected'; ?>>ES</option>
      <option value="fr" <?php if($lang=='fr')echo'selected'; ?>>FR</option>
      <option value="de" <?php if($lang=='de')echo'selected'; ?>>DE</option>
    </select>
  </label><br><br>

  <button type="submit">Save</button>
</form>
</body>
</html>
