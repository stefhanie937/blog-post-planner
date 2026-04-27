<?php
session_start();
if (isset($_SESSION['user_id'])) { header('Location: ../posts/read.php'); exit(); }
require_once '../config/db.php';
$message = ''; $msg_type = '';

function loginUser(string $email, string $password): string {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ? AND is_active = 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $id = $username = $hashed = null;
        $stmt->bind_result($id, $username, $hashed);
        $stmt->fetch();
        if (password_verify($password, $hashed)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            return 'success';
        }
    }
    return 'Invalid email or password!';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($email) || empty($password)) { $message = 'Please fill in all fields!'; $msg_type = 'error'; }
    else {
        $result = loginUser($email, $password);
        if ($result === 'success') { header('Location: ../posts/read.php'); exit(); }
        $message = $result; $msg_type = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In ✦ Blog Planner</title>
<?php include_once __DIR__ . '/../shared_style.php'; ?>
</head>
<body>
<div class="floating-deco">🌸</div>

<header>
  <div class="header-stars" id="headerStars"></div>
  <div class="header-inner">
    <div class="logo-area">
      <div class="logo-title">✦ BLOG PLANNER ✦</div>
      <div class="logo-subtitle">~ your cute writing companion ~</div>
    </div>
    <div class="header-deco">
      <a href="../index.php" class="pixel-badge">← HOME</a>
      <a href="register.php" class="pixel-badge lavender">REGISTER ✦</a>
    </div>
  </div>
</header>

<div class="nav-wrapper">
  <div class="nav-inner">
    <ul class="nav-tabs">
      <li class="nav-tab"><a href="../index.php">
        <svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="1" width="6" height="6"/><rect x="9" y="1" width="6" height="6"/><rect x="1" y="9" width="6" height="6"/><rect x="9" y="9" width="6" height="6"/></svg>DASHBOARD</a></li>
      <li class="nav-tab"><a href="../index.php">
        <svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="1" width="12" height="14"/><line x1="5" y1="5" x2="11" y2="5"/><line x1="5" y1="8" x2="11" y2="8"/><line x1="5" y1="11" x2="8" y2="11"/></svg>MY POSTS</a></li>
      <li class="nav-tab"><a href="../index.php">
        <svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="8" r="7"/><line x1="8" y1="4" x2="8" y2="12"/><line x1="4" y1="8" x2="12" y2="8"/></svg>NEW POST</a></li>
      <li class="nav-tab active"><a href="login.php">
        <svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="5" r="3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/></svg>ACCOUNT</a></li>
    </ul>
  </div>
</div>

<div class="main-wrapper">
  <div class="auth-wrap" style="padding:0;margin-top:2rem;">
    <div class="auth-container">
      <div class="auth-deco">
        <span class="auth-deco-icon">🌸</span>
        <div class="auth-deco-text">~ welcome back, blogger ~</div>
      </div>

      <div class="panel">
        <div class="panel-header">
          <span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>
          &nbsp;LOGIN.PHP — SIGN IN
        </div>
        <div class="panel-body">
          <?php if(!empty($message)): ?>
            <div class="alert <?= $msg_type ?>">
              <?= $msg_type==='error' ? '⚠' : '✓' ?> <?= htmlspecialchars($message) ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="form-group">
              <label>✦ EMAIL</label>
              <input type="email" name="email" placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
              <label>✦ PASSWORD</label>
              <input type="password" name="password" placeholder="your secret password~" required>
            </div>
            <div class="btn-row">
              <button type="submit" class="btn btn-primary">✦ SIGN IN</button>
              <a href="register.php" class="btn btn-secondary">REGISTER →</a>
            </div>
          </form>

          <div class="pixel-divider"></div>
          <p style="text-align:center;font-size:0.8rem;color:var(--text-light);font-weight:700;">
            no account? <a href="register.php" style="color:var(--pink-deep);text-decoration:none;font-weight:800;">create one! ✦</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const stars=['✦','✧','★','☆','✩'];
const c=document.getElementById('headerStars');
for(let i=0;i<12;i++){const s=document.createElement('span');s.textContent=stars[Math.floor(Math.random()*stars.length)];s.style.left=Math.random()*100+'%';s.style.top=Math.random()*100+'%';s.style.animationDelay=Math.random()*3+'s';c.appendChild(s);}
</script>
</body>
</html>
