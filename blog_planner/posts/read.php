<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: ../auth/login.php'); exit(); }
require_once '../config/db.php';

$userId = (int)$_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Writer';
$conn = getConnection();

// get ALL posts including deactivated for stats
$allStmt = $conn->prepare("SELECT status FROM blog_posts WHERE user_id = ?");
$allStmt->bind_param('i', $userId);
$allStmt->execute();
$allStmt->store_result();
$allStmt->bind_result($rowStatus);
$statTotal = $statPublished = $statDraft = $statDeact = 0;
while($allStmt->fetch()) {
    $statTotal++;
    if($rowStatus==='published') $statPublished++;
    elseif($rowStatus==='draft') $statDraft++;
    elseif($rowStatus==='deactivated') $statDeact++;
}
$allStmt->close();

// get active posts
$stmt = $conn->prepare
(
  "SELECT id, title, content, status, created_at FROM blog_posts 
  WHERE user_id = ? AND status != 'deactivated' ORDER BY created_at DESC"
);
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($pId, $pTitle, $pContent, $pStatus, $pCreatedAt);
$posts = [];
while($stmt->fetch()) {
    $posts[] = ['id'=>$pId,'title'=>$pTitle,'content'=>$pContent,'status'=>$pStatus,'created_at'=>$pCreatedAt];
}
$stmt->close();

// get ALL posts for "my posts" tab
$allPostsStmt = $conn->prepare("SELECT id, title, content, status, created_at FROM blog_posts WHERE user_id = ? ORDER BY created_at DESC");
$allPostsStmt->bind_param('i', $userId);
$allPostsStmt->execute();
$allPostsStmt->store_result();
$allPostsStmt->bind_result($apId, $apTitle, $apContent, $apStatus, $apCreatedAt);
$allPosts = [];
while($allPostsStmt->fetch()) {
    $allPosts[] = ['id'=>$apId,'title'=>$apTitle,'content'=>$apContent,'status'=>$apStatus,'created_at'=>$apCreatedAt];
}
$allPostsStmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard ✦ Blog Planner</title>
<?php include_once __DIR__ . '/../shared_style.php'; ?>
<style>
.page-section{display:none;} .page-section.active{display:block;}
.filter-bar{display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap;align-items:center;}
.filter-bar select,.filter-bar input{width:auto;}
.filter-bar input{flex:1;min-width:180px;}
.notes-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.note-box{border:3px solid var(--text-dark);padding:16px;}
.note-box.yellow{background:var(--yellow);}
.note-box.mint{background:var(--mint);}
.note-title{font-family:'Press Start 2P',monospace;font-size:9px;margin-bottom:10px;color:var(--text-dark);}
.note-box textarea{background:transparent;border:none;box-shadow:none;font-size:13px;padding:0;width:100%;min-height:80px;resize:vertical;outline:none;}
@media(max-width:600px){.notes-grid{grid-template-columns:1fr;}}
</style>
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
      <span style="font-size:0.85rem;font-weight:700;color:var(--text-mid);">hello, <strong style="color:var(--pink-deep);"><?= htmlspecialchars($username) ?></strong> ♡</span>
      <a href="../auth/logout.php" class="pixel-badge">SIGN OUT</a>
    </div>
  </div>
</header>

<div class="nav-wrapper">
  <div class="nav-inner">
    <ul class="nav-tabs" id="navTabs">
      <li class="nav-tab active" onclick="showTab('dashboard',this)">
        <svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="1" width="6" height="6"/><rect x="9" y="1" width="6" height="6"/><rect x="1" y="9" width="6" height="6"/><rect x="9" y="9" width="6" height="6"/></svg>
        DASHBOARD
      </li>
      <li class="nav-tab" onclick="showTab('posts',this)">
        <svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="1" width="12" height="14"/><line x1="5" y1="5" x2="11" y2="5"/><line x1="5" y1="8" x2="11" y2="8"/><line x1="5" y1="11" x2="8" y2="11"/></svg>
        MY POSTS
      </li>
      <li class="nav-tab" onclick="window.location='create.php'">
        <svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="8" r="7"/><line x1="8" y1="4" x2="8" y2="12"/><line x1="4" y1="8" x2="12" y2="8"/></svg>
        NEW POST
      </li>
      <li class="nav-tab" onclick="showTab('account',this)">
        <svg class="tab-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="5" r="3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/></svg>
        ACCOUNT
      </li>
    </ul>
  </div>
</div>

<div class="main-wrapper">

  <!-- DASHBOARD TAB -->
  <div class="page-section active" id="tab-dashboard">
    <div class="stats-row">
      <div class="stat-card s1"><span class="stat-icon">📝</span><span class="stat-num"><?= $statTotal ?></span><span class="stat-label">Total Posts</span></div>
      <div class="stat-card s2"><span class="stat-icon">✨</span><span class="stat-num"><?= $statPublished ?></span><span class="stat-label">Published</span></div>
      <div class="stat-card s3"><span class="stat-icon">🖊️</span><span class="stat-num"><?= $statDraft ?></span><span class="stat-label">Drafts</span></div>
      <div class="stat-card s4"><span class="stat-icon">💤</span><span class="stat-num"><?= $statDeact ?></span><span class="stat-label">Inactive</span></div>
    </div>

    <div class="panel">
      <div class="panel-header"><span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>&nbsp;RECENT POSTS</div>
      <div class="panel-body">
        <?php if(empty($posts)): ?>
          <div class="empty-state">
            <span class="empty-state-icon">📭</span>
            <div class="empty-state-text">no posts yet!<br>write your first one~ (｡◕‿◕｡)</div>
            <br><a href="create.php" class="btn btn-primary" style="margin-top:12px;">✦ NEW POST</a>
          </div>
        <?php else: ?>
          <div class="posts-grid">
            <?php foreach(array_slice($posts,0,4) as $post): ?>
              <div class="post-card">
                <div class="post-card-top <?= $post['status'] ?>"></div>
                <div class="post-card-body">
                  <div class="post-title"><?= htmlspecialchars($post['title']) ?></div>
                  <div class="post-content"><?= htmlspecialchars($post['content']) ?></div>
                  <div class="post-meta">
                    <span class="status-pill <?= $post['status'] ?>"><?= strtoupper($post['status']) ?></span>
                    <span>📅 <?= date('Y-m-d', strtotime($post['created_at'])) ?></span>
                  </div>
                  <div class="post-actions">
                    <a href="update.php?id=<?= $post['id'] ?>" class="btn-sm edit">- EDIT</a>
                    <a href="deactivate.php?id=<?= $post['id'] ?>" class="btn-sm deact" onclick="return confirm('Deactivate this post?')">✕ DEACTIVATE</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="panel">
      <div class="panel-header"><span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>&nbsp;QUICK NOTES ✦ YOUR WRITING TRACKER</div>
      <div class="panel-body">
        <div class="pixel-divider"></div>
        <div class="notes-grid">
          <div class="note-box yellow"><div class="note-title">📌 TODAY'S GOALS</div><textarea id="todayGoals" placeholder="Write your blogging goals for today..."></textarea></div>
          <div class="note-box mint"><div class="note-title">💡 IDEAS BOX</div><textarea id="ideasBox" placeholder="Jot your next post ideas here..."></textarea></div>
        </div>
      </div>
    </div>
  </div>

  <!-- MY POSTS TAB -->
  <div class="page-section" id="tab-posts">
    <div class="panel">
      <div class="panel-header"><span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>&nbsp;ALL BLOG POSTS</div>
      <div class="panel-body">
        <div class="filter-bar">
          <select id="filterStatus" onchange="filterPosts()">
            <option value="">✦ All Status</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
            <option value="deactivated">Deactivated</option>
          </select>
          <input type="text" id="searchInput" oninput="filterPosts()" placeholder="🔍 Search posts...">
          <a href="create.php" class="btn btn-primary">＋ NEW POST</a>
        </div>
        <div id="filteredPosts" class="posts-grid">
          <?php foreach($allPosts as $post): ?>
            <div class="post-card" data-status="<?= $post['status'] ?>" data-title="<?= strtolower(htmlspecialchars($post['title'])) ?>">
              <div class="post-card-top <?= $post['status'] ?>"></div>
              <div class="post-card-body">
                <div class="post-title"><?= htmlspecialchars($post['title']) ?></div>
                <div class="post-content"><?= htmlspecialchars($post['content']) ?></div>
                <div class="post-meta">
                  <span class="status-pill <?= $post['status'] ?>"><?= strtoupper($post['status']) ?></span>
                  <span>📅 <?= date('Y-m-d', strtotime($post['created_at'])) ?></span>
                </div>
                <div class="post-actions">
                  <?php if($post['status'] !== 'deactivated'): ?>
                    <a href="update.php?id=<?= $post['id'] ?>" class="btn-sm edit">- EDIT</a>
                    <a href="deactivate.php?id=<?= $post['id'] ?>" class="btn-sm deact" onclick="return confirm('Deactivate?')">✕ DEACTIVATE</a>
                  <?php else: ?>
                    <a href="update.php?id=<?= $post['id'] ?>" class="btn-sm edit">- EDIT</a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- ACCOUNT TAB -->
  <div class="page-section" id="tab-account">
    <div class="panel">
      <div class="panel-header"><span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>&nbsp;ACCOUNT INFO</div>
      <div class="panel-body" style="text-align:center;padding:2rem;">
        <div style="font-size:3rem;margin-bottom:1rem;">👤</div>
        <div style="font-family:'Press Start 2P',monospace;font-size:0.8rem;color:var(--text-dark);line-height:2;margin-bottom:0.5rem;"><?= htmlspecialchars($username) ?></div>
        <div class="pixel-divider"></div>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:1rem;">
          <a href="create.php" class="btn btn-primary">✦ NEW POST</a>
          <a href="../auth/logout.php" class="btn btn-danger">SIGN OUT ✕</a>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
const stars=['✦','✧','★','☆','✩'];
const c=document.getElementById('headerStars');
for(let i=0;i<12;i++){const s=document.createElement('span');s.textContent=stars[Math.floor(Math.random()*stars.length)];s.style.left=Math.random()*100+'%';s.style.top=Math.random()*100+'%';s.style.animationDelay=Math.random()*3+'s';c.appendChild(s);}

function showTab(name, el) {
  document.querySelectorAll('.page-section').forEach(s=>s.classList.remove('active'));
  document.querySelectorAll('.nav-tab').forEach(t=>t.classList.remove('active'));
  document.getElementById('tab-'+name).classList.add('active');
  if(el) el.classList.add('active');
}

function filterPosts() {
  const status = document.getElementById('filterStatus').value;
  const search = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('#filteredPosts .post-card').forEach(card => {
    const matchStatus = !status || card.dataset.status === status;
    const matchSearch = !search || card.dataset.title.includes(search);
    card.style.display = matchStatus && matchSearch ? '' : 'none';
  });
}

const goalsKey='bp_goals', ideasKey='bp_ideas';
const goalsEl=document.getElementById('todayGoals'), ideasEl=document.getElementById('ideasBox');
if(goalsEl){ goalsEl.value=localStorage.getItem(goalsKey)||''; goalsEl.oninput=()=>localStorage.setItem(goalsKey,goalsEl.value); }
if(ideasEl){ ideasEl.value=localStorage.getItem(ideasKey)||''; ideasEl.oninput=()=>localStorage.setItem(ideasKey,ideasEl.value); }
</script>
</body>
</html>