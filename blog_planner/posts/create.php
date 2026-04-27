<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: ../auth/login.php'); exit(); }
require_once '../config/db.php';
$message=''; $msg_type='';

function createPost(int $userId, string $title, string $content, string $status): string {
    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO blog_posts (user_id,title,content,status) VALUES (?,?,?,?)");
    $stmt->bind_param('isss',$userId,$title,$content,$status);
    return $stmt->execute() ? 'success' : $stmt->error;
}

// In the POST handler:
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title=trim($_POST['title']??''); $content=trim($_POST['content']??''); $status=$_POST['status']??'draft';
    if(empty($title)||empty($content)){ $message='Please fill in title and content!'; $msg_type='error'; }
    else {
        $result=createPost((int)$_SESSION['user_id'],$title,$content,$status);
        if($result==='success'){ header('Location: read.php'); exit(); }
        $message='Error: '.$result; $msg_type='error';
    }
}
$username = $_SESSION['username'] ?? 'Writer';
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Post ✦ Blog Planner</title>
<?php include_once __DIR__ . '/../shared_style.php'; ?>
<style>
.tips-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:12px;}
.tip-card{border:2px solid var(--text-dark);padding:12px;font-size:12px;line-height:1.6;}
.tip-card.p{background:var(--pink-light);} .tip-card.l{background:#eee0ff;} .tip-card.m{background:var(--mint);}
.tip-card-title{font-family:'Press Start 2P',monospace;font-size:8px;margin-bottom:6px;color:var(--text-dark);}
@media(max-width:600px){.tips-grid{grid-template-columns:1fr;}}
</style>
</head>
<body>
<div class="floating-deco">✍️</div>

<header>
  <div class="header-stars" id="headerStars"></div>
  <div class="header-inner">
    <div class="logo-area">
      <div class="logo-title">✦ BLOG PLANNER ✦</div>
      <div class="logo-subtitle">~ your cute writing companion ~</div>
    </div>
    <div class="header-deco">
      <span style="font-size:0.85rem;font-weight:700;color:var(--text-mid);">hello, <strong style="color:var(--pink-deep);"><?= htmlspecialchars($username) ?></strong> ♡</span>
      <a href="../auth/logout.php" class="pixel-badge">SIGN OUT</a>
    </div>
  </div>
</header>

<div class="nav-wrapper">
  <div class="nav-inner">
    <ul class="nav-tabs">
      <li class="nav-tab" onclick="window.location='read.php'"><svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="1" width="6" height="6"/><rect x="9" y="1" width="6" height="6"/><rect x="1" y="9" width="6" height="6"/><rect x="9" y="9" width="6" height="6"/></svg>DASHBOARD</li>
      <li class="nav-tab" onclick="window.location='read.php'"><svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="1" width="12" height="14"/><line x1="5" y1="5" x2="11" y2="5"/><line x1="5" y1="8" x2="11" y2="8"/><line x1="5" y1="11" x2="8" y2="11"/></svg>MY POSTS</li>
      <li class="nav-tab active"><svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="8" r="7"/><line x1="8" y1="4" x2="8" y2="12"/><line x1="4" y1="8" x2="12" y2="8"/></svg>NEW POST</li>
      <li class="nav-tab" onclick="window.location='read.php'"><svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="5" r="3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/></svg>ACCOUNT</li>
    </ul>
  </div>
</div>

<div class="main-wrapper">
  <div class="panel">
    <div class="panel-header">
      <span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>
      &nbsp;CREATE NEW POST ✦ NEW FILE
    </div>
    <div class="panel-body">
      <?php if(!empty($message)): ?>
        <div class="alert <?= $msg_type ?>"><?= $msg_type==='error'?'⚠':'✓' ?> <?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-group">
          <label>✦ POST TITLE</label>
          <input type="text" name="title" placeholder="What's the title of your post?" value="<?= htmlspecialchars($_POST['title']??'') ?>" required>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div class="form-group">
            <label>✦ STATUS</label>
            <select name="status">
              <option value="draft">📝 Draft</option>
              <option value="published">✨ Published</option>
            </select>
          </div>
          <div class="form-group">
            <label>✦ CATEGORY (optional)</label>
            <input type="text" name="category" placeholder="e.g. lifestyle, tech, food">
          </div>
        </div>
        <div class="form-group">
          <label>✦ CONTENT</label>
          <textarea name="content" placeholder="Start writing your awesome blog post here~ ✨" style="min-height:200px;" required><?= htmlspecialchars($_POST['content']??'') ?></textarea>
        </div>
        <div class="btn-row">
          <button type="submit" name="status" value="published" class="btn btn-primary">✦ PUBLISH POST</button>
          <button type="submit" name="status" value="draft" class="btn btn-secondary">💾 SAVE DRAFT</button>
          <a href="read.php" class="btn btn-ghost">✕ CLEAR</a>
        </div>
      </form>
    </div>
  </div>

  <div class="panel">
    <div class="panel-header">
      <span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>
      &nbsp;✦ WRITING TIPS
    </div>
    <div class="panel-body">
      <div class="tips-grid">
        <div class="tip-card p"><div class="tip-card-title">HOOK YOUR READER</div>Start with a question, story, or bold statement to grab attention right away!</div>
        <div class="tip-card l"><div class="tip-card-title">USE SHORT PARAS</div>Keep paragraphs to 2-3 sentences for an easy, breezy reading experience~</div>
        <div class="tip-card m"><div class="tip-card-title">END WITH A CTA</div>Always end with a call to action — ask for comments, shares, or feedback!</div>
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
