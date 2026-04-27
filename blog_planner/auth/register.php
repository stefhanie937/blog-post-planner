<?php
session_start();
if (isset($_SESSION['user_id'])) { header('Location: ../posts/read.php'); exit(); }
require_once '../config/db.php';
$message = ''; $msg_type = '';

function registerUser(string $username, string $email, string $password): string {
    $conn = getConnection();
    $c=$conn->prepare("SELECT id FROM users WHERE email=?"); $c->bind_param('s',$email); $c->execute(); $c->store_result();
    if($c->num_rows>0) return 'error:That email is already registered!';
    $c2=$conn->prepare("SELECT id FROM users WHERE username=?"); $c2->bind_param('s',$username); $c2->execute(); $c2->store_result();
    if($c2->num_rows>0) return 'error:That username is already taken!';
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $stmt=$conn->prepare("INSERT INTO users (username,email,password) VALUES (?,?,?)");
    $stmt->bind_param('sss',$username,$email,$hashed);
    if($stmt->execute()) return 'success:Account created! You can now sign in ✿';
    return 'error:Something went wrong. Please try again!';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username=trim($_POST['username']??''); $email=trim($_POST['email']??'');
    $password=$_POST['password']??''; $confirm=$_POST['confirm_password']??'';
    if(empty($username)||empty($email)||empty($password)){ $message='Please fill in all fields!'; $msg_type='error'; }
    elseif($password!==$confirm){ $message='Passwords do not match!'; $msg_type='error'; }
    elseif(strlen($password)<6){ $message='Password needs 6+ characters!'; $msg_type='error'; }
    else { $result=registerUser($username,$email,$password); list($msg_type,$message) = explode(':', $result, 2); }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register ✦ Blog Planner</title>
<?php include_once __DIR__ . '/../shared_style.php'; ?>
</head>
<body>
<div class="floating-deco">✨</div>

<header>
  <div class="header-stars" id="headerStars"></div>
  <div class="header-inner">
    <div class="logo-area">
      <div class="logo-title">✦ BLOG PLANNER ✦</div>
      <div class="logo-subtitle">~ your cute writing companion ~</div>
    </div>
    <div class="header-deco">
      <a href="../index.php" class="pixel-badge">← HOME</a>
      <a href="login.php" class="pixel-badge pink">LOGIN ✦</a>
    </div>
  </div>
</header>

<div class="nav-wrapper">
  <div class="nav-inner">
    <ul class="nav-tabs">
      <li class="nav-tab"><a href="../index.php"><svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="1" width="6" height="6"/><rect x="9" y="1" width="6" height="6"/><rect x="1" y="9" width="6" height="6"/><rect x="9" y="9" width="6" height="6"/></svg>DASHBOARD</a></li>
      <li class="nav-tab"><a href="../index.php"><svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="1" width="12" height="14"/><line x1="5" y1="5" x2="11" y2="5"/><line x1="5" y1="8" x2="11" y2="8"/><line x1="5" y1="11" x2="8" y2="11"/></svg>MY POSTS</a></li>
      <li class="nav-tab"><a href="../index.php"><svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="8" r="7"/><line x1="8" y1="4" x2="8" y2="12"/><line x1="4" y1="8" x2="12" y2="8"/></svg>NEW POST</a></li>
      <li class="nav-tab active"><a href="register.php"><svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="5" r="3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/></svg>ACCOUNT</a></li>
    </ul>
  </div>
</div>

<div class="main-wrapper">
  <div class="auth-wrap" style="padding:0;margin-top:2rem;">
    <div class="auth-container">
      <div class="auth-deco">
        <span class="auth-deco-icon">🌷</span>
        <div class="auth-deco-text">~ join the writing adventure ~</div>
      </div>

      <div class="panel">
        <div class="panel-header">
          <span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>
          &nbsp;REGISTER.PHP — CREATE ACCOUNT
        </div>
        <div class="panel-body">
          <?php if(!empty($message)): ?>
            <div class="alert <?= $msg_type ?>">
              <?= $msg_type==='error'?'⚠':'✓' ?> <?= htmlspecialchars($message) ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="form-group">
              <label>✦ USERNAME</label>
              <input type="text" name="username" placeholder="YourCuteName" value="<?= htmlspecialchars($_POST['username']??'') ?>" required>
            </div>
            <div class="form-group">
              <label>✦ EMAIL</label>
              <input type="email" name="email" placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email']??'') ?>" required>
            </div>
            <div class="form-group">
              <label>✦ PASSWORD</label>
              <input type="password" name="password" placeholder="create a strong password" required>
            </div>
            <div class="form-group">
              <label>✦ CONFIRM PASSWORD</label>
              <input type="password" name="confirm_password" placeholder="repeat your password" required>
            </div>
            <div class="btn-row">
              <button type="submit" class="btn btn-primary">✦ CREATE ACCOUNT</button>
              <a href="login.php" class="btn btn-ghost">· BACK TO LOGIN</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const stars=['✦','✧','★','☆','✩'];const c=document.getElementById('headerStars');
for(let i=0;i<12;i++){const s=document.createElement('span');s.textContent=stars[Math.floor(Math.random()*stars.length)];s.style.left=Math.random()*100+'%';s.style.top=Math.random()*100+'%';s.style.animationDelay=Math.random()*3+'s';c.appendChild(s);}
</script>
</body>
</html>
