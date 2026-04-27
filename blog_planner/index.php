<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: posts/read.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>✦ Blog Planner ✦</title>
<?php include_once __DIR__ . '/shared_style.php'; ?>
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
      <a href="auth/login.php" class="pixel-badge pink">LOGIN ✦</a>
      <a href="auth/register.php" class="pixel-badge lavender">REGISTER ✦</a>
    </div>
  </div>
</header>

<div class="nav-wrapper">
  <div class="nav-inner">
    <ul class="nav-tabs">
      <li class="nav-tab active"><a href="auth/login.php">
        <svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="1" width="6" height="6"/><rect x="9" y="1" width="6" height="6"/><rect x="1" y="9" width="6" height="6"/><rect x="9" y="9" width="6" height="6"/></svg>
        DASHBOARD
      </a></li>
      <li class="nav-tab"><a href="auth/login.php">
        <svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="1" width="12" height="14"/><line x1="5" y1="5" x2="11" y2="5"/><line x1="5" y1="8" x2="11" y2="8"/><line x1="5" y1="11" x2="8" y2="11"/></svg>
        MY POSTS
      </a></li>
      <li class="nav-tab"><a href="auth/login.php">
        <svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="8" r="7"/><line x1="8" y1="4" x2="8" y2="12"/><line x1="4" y1="8" x2="12" y2="8"/></svg>
        NEW POST
      </a></li>
      <li class="nav-tab"><a href="auth/login.php">
        <svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="5" r="3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/></svg>
        ACCOUNT
      </a></li>
    </ul>
  </div>
</div>

<div class="main-wrapper">
  <div style="text-align:center;padding:3rem 1rem;">
    <div style="font-size:4rem;margin-bottom:1rem;animation:bounce 1.5s ease-in-out infinite;">📓</div>
    <div style="font-family:'Press Start 2P',monospace;font-size:1rem;color:var(--text-dark);line-height:2;margin-bottom:1rem;text-shadow:2px 2px 0 var(--pink-mid);">WELCOME TO BLOG PLANNER!</div>
    <div class="pixel-divider"></div>
    <p style="font-size:0.95rem;color:var(--text-mid);font-weight:700;margin-bottom:2rem;">your kawaii space to write, plan & publish ✨</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
      <a href="auth/login.php" class="btn btn-primary">▶ SIGN IN</a>
      <a href="auth/register.php" class="btn btn-secondary">✦ CREATE ACCOUNT</a>
    </div>
  </div>
</div>

<script>
// twinkling stars in header
const stars = ['✦','✧','★','☆','✩','✫'];
const container = document.getElementById('headerStars');
for(let i=0;i<12;i++){
  const s=document.createElement('span');
  s.textContent=stars[Math.floor(Math.random()*stars.length)];
  s.style.left=Math.random()*100+'%';
  s.style.top=Math.random()*100+'%';
  s.style.animationDelay=Math.random()*3+'s';
  s.style.fontSize=(12+Math.random()*12)+'px';
  container.appendChild(s);
}
</script>
</body>
</html>
