<?php
// shared_style.php — include at top of every page for consistent kawaii UI
// Usage: include_once __DIR__ . '/../shared_style.php';  (adjust path as needed)
?>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --pink-light: #ffd6e7;
  --pink-mid: #ffb3d1;
  --pink-dark: #ff85b3;
  --pink-deep: #e05c96;
  --lavender: #d4b8ff;
  --lavender-mid: #b990f5;
  --lavender-dark: #8b5cf6;
  --mint: #b8f0e0;
  --mint-dark: #4ecba0;
  --sky: #b8e4ff;
  --sky-dark: #60b4f5;
  --cream: #fff9f0;
  --cream2: #fff4e6;
  --peach: #ffd6b8;
  --yellow: #fff0a0;
  --yellow-dark: #f5c842;
  --border-color: #e8b4d0;
  --text-dark: #3d2060;
  --text-mid: #6b3d8a;
  --text-light: #9b6bba;
  --shadow: 4px 4px 0px #c87ab0;
  --shadow-soft: 3px 3px 0px #d4a0c0;
}
*{box-sizing:border-box;margin:0;padding:0;}
body {
  font-family:'Nunito',sans-serif;
  background-color:#fff0f8;
  background-image:
    radial-gradient(circle at 20% 20%,rgba(255,182,229,0.3) 0%,transparent 40%),
    radial-gradient(circle at 80% 80%,rgba(196,165,255,0.3) 0%,transparent 40%);
  min-height:100vh;
  color:var(--text-dark);
}
/* scrollbar */
::-webkit-scrollbar{width:8px;}
::-webkit-scrollbar-track{background:var(--cream);}
::-webkit-scrollbar-thumb{background:var(--pink-dark);border:2px solid var(--text-dark);}

/* HEADER */
header {
  background:linear-gradient(135deg,#ffc6e8 0%,#e8c6ff 50%,#c6e8ff 100%);
  border-bottom:3px solid var(--text-dark);
  position:relative; overflow:hidden;
}
.header-stars{position:absolute;inset:0;pointer-events:none;overflow:hidden;}
.header-stars span{position:absolute;font-size:18px;animation:twinkle 3s ease-in-out infinite;opacity:0.7;}
@keyframes twinkle{0%,100%{opacity:0.3;transform:scale(0.8);}50%{opacity:1;transform:scale(1.2);}}
.header-inner{max-width:1100px;margin:0 auto;padding:24px 24px 16px;display:flex;align-items:center;justify-content:space-between;position:relative;z-index:1;}
.logo-area{display:flex;flex-direction:column;gap:6px;}
.logo-title{font-family:'Press Start 2P',monospace;font-size:14px;color:var(--text-dark);text-shadow:2px 2px 0 #fff,3px 3px 0 var(--pink-dark);line-height:1.5;}
.logo-subtitle{font-size:13px;color:var(--text-mid);font-weight:700;letter-spacing:1px;}
.header-deco{display:flex;gap:10px;align-items:center;}
.pixel-badge{font-family:'Press Start 2P',monospace;font-size:9px;padding:6px 10px;border:2px solid var(--text-dark);box-shadow:2px 2px 0 var(--text-dark);background:var(--yellow);color:var(--text-dark);cursor:pointer;transition:all 0.1s;text-decoration:none;display:inline-block;}
.pixel-badge:hover{transform:translate(-1px,-1px);box-shadow:3px 3px 0 var(--text-dark);}
.pixel-badge:active{transform:translate(2px,2px);box-shadow:none;}
.pixel-badge.pink{background:var(--pink-mid);}
.pixel-badge.lavender{background:var(--lavender);}

/* NAV */
.nav-wrapper{border-bottom:3px solid var(--text-dark);background:var(--cream);}
.nav-inner{max-width:1100px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;}
.nav-tabs{display:flex;gap:0;list-style:none;}
.nav-tab{font-family:'Press Start 2P',monospace;font-size:9px;padding:12px 16px;border-right:2px solid var(--border-color);cursor:pointer;color:var(--text-light);transition:all 0.1s;display:flex;align-items:center;gap:6px;}
.nav-tab:hover{background:var(--pink-light);color:var(--text-dark);}
.nav-tab.active{background:var(--pink-mid);color:var(--text-dark);border-bottom:3px solid var(--pink-dark);margin-bottom:-3px;}
.nav-tab a{text-decoration:none;color:inherit;display:flex;align-items:center;gap:6px;}
.tab-icon{width:16px;height:16px;display:inline-block;}
.nav-user-info{font-size:11px;font-weight:700;color:var(--text-mid);padding:0 12px;}
.nav-user-info span{color:var(--pink-deep);}

/* MAIN */
.main-wrapper{max-width:1100px;margin:0 auto;padding:24px;}

/* PANEL */
.panel{background:#fff;border:3px solid var(--text-dark);box-shadow:var(--shadow);margin-bottom:24px;overflow:hidden;}
.panel-header{background:linear-gradient(90deg,var(--pink-light),var(--lavender));border-bottom:3px solid var(--text-dark);padding:10px 16px;display:flex;align-items:center;gap:10px;font-family:'Press Start 2P',monospace;font-size:10px;color:var(--text-dark);}
.panel-header .dot{width:10px;height:10px;border:2px solid var(--text-dark);border-radius:50%;display:inline-block;}
.dot.red{background:#ff6b6b;} .dot.yellow{background:var(--yellow-dark);} .dot.green{background:var(--mint-dark);}
.panel-body{padding:20px;}

/* STATS */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
.stat-card{border:3px solid var(--text-dark);box-shadow:var(--shadow-soft);padding:14px;text-align:center;position:relative;overflow:hidden;}
.stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;}
.stat-card.s1::before{background:var(--pink-dark);} .stat-card.s2::before{background:var(--lavender-dark);}
.stat-card.s3::before{background:var(--mint-dark);} .stat-card.s4::before{background:var(--yellow-dark);}
.stat-card.s1{background:var(--pink-light);} .stat-card.s2{background:#eee0ff;}
.stat-card.s3{background:var(--mint);} .stat-card.s4{background:var(--yellow);}
.stat-num{font-family:'Press Start 2P',monospace;font-size:20px;color:var(--text-dark);display:block;margin-bottom:6px;}
.stat-label{font-size:11px;font-weight:700;color:var(--text-mid);text-transform:uppercase;letter-spacing:0.5px;}
.stat-icon{font-size:22px;display:block;margin-bottom:4px;}

/* FORM */
.form-group{display:flex;flex-direction:column;gap:6px;margin-bottom:14px;}
.form-group.full{grid-column:1/-1;}
label{font-family:'Press Start 2P',monospace;font-size:9px;color:var(--text-mid);display:flex;align-items:center;gap:6px;}
input[type="text"],input[type="email"],input[type="password"],textarea,select{width:100%;padding:10px 12px;border:3px solid var(--text-dark);box-shadow:3px 3px 0 var(--border-color);font-family:'Nunito',sans-serif;font-size:14px;background:var(--cream);color:var(--text-dark);outline:none;transition:all 0.15s;}
input:focus,textarea:focus,select:focus{background:#fff;box-shadow:3px 3px 0 var(--pink-dark);border-color:var(--pink-deep);}
textarea{resize:vertical;min-height:160px;}

/* BUTTONS */
.btn{font-family:'Press Start 2P',monospace;font-size:10px;padding:10px 18px;border:3px solid var(--text-dark);cursor:pointer;transition:all 0.1s;display:inline-flex;align-items:center;gap:8px;text-decoration:none;}
.btn:hover{transform:translate(-2px,-2px);}
.btn:active{transform:translate(2px,2px);box-shadow:none!important;}
.btn-primary{background:var(--pink-dark);color:#fff;box-shadow:4px 4px 0 var(--pink-deep);}
.btn-secondary{background:var(--lavender);color:var(--text-dark);box-shadow:4px 4px 0 var(--lavender-dark);}
.btn-success{background:var(--mint);color:var(--text-dark);box-shadow:4px 4px 0 var(--mint-dark);}
.btn-danger{background:#ffc0b8;color:#8b1a1a;box-shadow:4px 4px 0 #e05050;}
.btn-ghost{background:var(--cream);color:var(--text-dark);box-shadow:4px 4px 0 var(--border-color);}
.btn-row{display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;}

/* POSTS */
.posts-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;}
.post-card{border:3px solid var(--text-dark);box-shadow:var(--shadow-soft);background:#fff;overflow:hidden;transition:transform 0.1s;}
.post-card:hover{transform:translate(-2px,-2px);box-shadow:6px 6px 0 var(--border-color);}
.post-card-top{height:8px;}
.post-card-top.published{background:repeating-linear-gradient(90deg,var(--mint-dark) 0,var(--mint-dark) 8px,transparent 8px,transparent 16px);}
.post-card-top.draft{background:repeating-linear-gradient(90deg,var(--yellow-dark) 0,var(--yellow-dark) 8px,transparent 8px,transparent 16px);}
.post-card-top.deactivated{background:repeating-linear-gradient(90deg,#ccc 0,#ccc 8px,transparent 8px,transparent 16px);}
.post-card-body{padding:14px;}
.post-title{font-family:'Press Start 2P',monospace;font-size:10px;color:var(--text-dark);line-height:1.6;margin-bottom:8px;}
.post-content{font-size:13px;color:var(--text-mid);margin-bottom:12px;line-height:1.6;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;}
.post-meta{font-size:11px;color:var(--text-light);font-weight:700;margin-bottom:10px;display:flex;gap:10px;flex-wrap:wrap;}
.post-actions{display:flex;gap:8px;flex-wrap:wrap;}
.status-pill{font-family:'Press Start 2P',monospace;font-size:8px;padding:3px 8px;border:2px solid var(--text-dark);display:inline-block;}
.status-pill.published{background:var(--mint);color:#1a6b48;}
.status-pill.draft{background:var(--yellow);color:#7a5c00;}
.status-pill.deactivated{background:#e8e8e8;color:#666;}
.btn-sm{font-family:'Press Start 2P',monospace;font-size:8px;padding:6px 10px;border:2px solid var(--text-dark);cursor:pointer;transition:all 0.1s;text-decoration:none;display:inline-block;}
.btn-sm:hover{transform:translate(-1px,-1px);}
.btn-sm.edit{background:var(--lavender);box-shadow:2px 2px 0 var(--text-dark);}
.btn-sm.deact{background:#ffc0b8;box-shadow:2px 2px 0 var(--text-dark);}

/* ALERTS */
.alert{border:3px solid var(--text-dark);padding:12px 16px;font-family:'Press Start 2P',monospace;font-size:9px;line-height:1.8;margin-bottom:16px;display:flex;align-items:center;gap:10px;}
.alert.success{background:var(--mint);box-shadow:3px 3px 0 var(--mint-dark);}
.alert.error{background:#ffc0b8;box-shadow:3px 3px 0 #e05050;}

/* DIVIDER */
.pixel-divider{height:3px;background:repeating-linear-gradient(90deg,var(--pink-mid) 0,var(--pink-mid) 8px,var(--lavender) 8px,var(--lavender) 16px,var(--mint) 16px,var(--mint) 24px,var(--sky) 24px,var(--sky) 32px);margin:20px 0;border-top:1px solid var(--text-dark);border-bottom:1px solid var(--text-dark);}

/* AUTH */
.auth-wrap{min-height:calc(100vh - 120px);display:flex;align-items:center;justify-content:center;padding:2rem;}
.auth-container{width:100%;max-width:440px;}
.auth-deco{text-align:center;margin-bottom:20px;}
.auth-deco-icon{font-size:3rem;display:block;margin-bottom:8px;animation:bounce 1.5s ease-in-out infinite;}
@keyframes bounce{0%,100%{transform:translateY(0);}50%{transform:translateY(-8px);}}
.auth-deco-text{font-family:'Press Start 2P',monospace;font-size:9px;color:var(--text-light);line-height:2;}

/* SECTION TITLE */
.section-title{font-family:'Press Start 2P',monospace;font-size:11px;color:var(--text-dark);margin-bottom:16px;display:flex;align-items:center;gap:10px;}
.section-title::after{content:'';flex:1;height:3px;background:repeating-linear-gradient(90deg,var(--pink-mid) 0,var(--pink-mid) 6px,transparent 6px,transparent 12px);}

/* EMPTY STATE */
.empty-state{text-align:center;padding:48px 24px;border:3px dashed var(--border-color);}
.empty-state-icon{font-size:48px;margin-bottom:12px;display:block;}
.empty-state-text{font-family:'Press Start 2P',monospace;font-size:10px;color:var(--text-light);line-height:2;}

/* FLOATING DECO */
.floating-deco{position:fixed;bottom:20px;right:20px;font-size:28px;animation:float 4s ease-in-out infinite;pointer-events:none;filter:drop-shadow(2px 4px 0 rgba(180,100,150,0.4));z-index:10;}
@keyframes float{0%,100%{transform:translateY(0);}50%{transform:translateY(-8px);}}

@media(max-width:640px){
  .stats-row{grid-template-columns:repeat(2,1fr);}
  .posts-grid{grid-template-columns:1fr;}
  .header-inner{flex-direction:column;gap:12px;align-items:flex-start;}
}
</style>
