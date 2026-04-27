<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: ../auth/login.php'); exit(); }
require_once '../config/db.php';
$message=''; $post=null;
$postId=(int)($_GET['id']??$_POST['post_id']??0);

function getPost(int $postId, int $userId): ?array {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id, title, content, status FROM blog_posts WHERE id=? AND user_id=?");
    $stmt->bind_param('ii', $postId, $userId);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $id = $title = $content = $status = null;
        $stmt->bind_result($id, $title, $content, $status);
        $stmt->fetch();
        return ['id'=>$id,'title'=>$title,'content'=>$content,'status'=>$status];
    }
    return null;
}

function updatePost(int $postId, int $userId, string $title, string $content, string $status): string {
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE blog_posts SET title=?,content=?,status=? WHERE id=? AND user_id=?");
    $stmt->bind_param('sssii', $title, $content, $status, $postId, $userId);
    return $stmt->execute() ? 'success' : $stmt->error;
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title = trim($_POST['title']??'');
    $content = trim($_POST['content']??'');
    $status = $_POST['status']??'draft';
    if (empty($title)||empty($content)) { $message='Please fill in all fields!'; }
    else {
        $r = updatePost($postId, (int)$_SESSION['user_id'], $title, $content, $status);
        if ($r==='success') { header('Location: read.php'); exit(); }
        $message = 'Error: '.$r;
    }
}
$post = getPost($postId, (int)$_SESSION['user_id']);
if (!$post) { header('Location: read.php'); exit(); }
$username = $_SESSION['username']??'Writer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Post ✦ Blog Planner</title>
<?php include_once __DIR__ . '/../shared_style.php'; ?>
</head>
<body>
<div class="floating-deco">✏️</div>

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
      <li class="nav-tab active" onclick="window.location='read.php'"><svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="1" width="12" height="14"/><line x1="5" y1="5" x2="11" y2="5"/><line x1="5" y1="8" x2="11" y2="8"/><line x1="5" y1="11" x2="8" y2="11"/></svg>MY POSTS</li>
      <li class="nav-tab" onclick="window.location='create.php'"><svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="8" r="7"/><line x1="8" y1="4" x2="8" y2="12"/><line x1="4" y1="8" x2="12" y2="8"/></svg>NEW POST</li>
      <li class="nav-tab" onclick="window.location='read.php'"><svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="5" r="3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/></svg>ACCOUNT</li>
    </ul>
  </div>
</div>

<div class="main-wrapper">
  <div class="panel">
    <div class="panel-header">
      <span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>
      &nbsp;EDIT_POST.PHP — EDITING: <?= htmlspecialchars(strtoupper($post['title'])) ?>
    </div>
    <div class="panel-body">
      <?php if(!empty($message)): ?>
        <div class="alert error">⚠ <?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
        <div class="form-group">
          <label>✦ POST TITLE</label>
          <input type="text" name="title" value="<?= htmlspecialchars($_POST['title']??$post['title']) ?>" required>
        </div>
        <div class="form-group">
          <label>✦ STATUS</label>
          <select name="status">
            <?php foreach(['draft'=>'📝 Draft','published'=>'✨ Published'] as $val=>$lbl): ?>
              <option value="<?= $val ?>" <?= $post['status']===$val?'selected':'' ?>><?= $lbl ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>✦ CONTENT</label>
          <textarea name="content" style="min-height:200px;" required><?= htmlspecialchars($_POST['content']??$post['content']) ?></textarea>
        </div>
        <div class="btn-row">
          <button type="submit" class="btn btn-primary">💾 SAVE CHANGES</button>
          <a href="read.php" class="btn btn-ghost">← CANCEL</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
const stars=['✦','✧','★','☆','✩'];const c=document.getElementById('headerStars');
for(let i=0;i<12;i++){const s=document.createElement('span');s.textContent=stars[Math.floor(Math.random()*stars.length)];s.style.left=Math.random()*100+'%';s.style.top=Math.random()*100+'%';s.style.animationDelay=Math.random()*3+'s';c.appendChild(s);}
</script>
</body>
</html>