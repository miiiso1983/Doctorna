<?php /** @var string $title */ ?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? App\Core\e($title) . ' | ' : '' ?>طبيبنا</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <style>
    :root{ --brand:#0d6efd; }
    body{ background: linear-gradient(180deg, #f8f9fb 0%, #ffffff 40%); }
    .navbar{ box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .card{ border: 0; box-shadow: 0 2px 16px rgba(0,0,0,.06); border-radius: 12px; }
    .btn-primary{ background: var(--brand); border-color: var(--brand); }
    .badge{ text-transform: capitalize; }
    .app-footer{ color:#6c757d; }
    .dark body{ background: #0b0f19; }
    .dark .navbar{ background-color: #111827 !important; }
    .dark .card{ background: #0f172a; color: #e5e7eb; box-shadow: none; border: 1px solid #1f2937; }
    .dark .btn-primary{ background: #2563eb; border-color: #2563eb; }
    .dark .app-footer{ color:#9ca3af; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="<?= $baseUrl ?>/"><?= App\Core\Lang::t('app.name') ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExample07">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="<?= $baseUrl ?>/"><?= App\Core\Lang::t('nav.home') ?></a></li>
        <?php if (!empty($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="<?= $baseUrl ?>/dashboard"><?= App\Core\Lang::t('nav.dashboard') ?></a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex">
        <div class="dropdown me-3">
          <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><?= App\Core\Lang::t('nav.language') ?></button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= $baseUrl ?>/lang?l=ar">العربية</a></li>
            <li><a class="dropdown-item" href="<?= $baseUrl ?>/lang?l=en">English</a></li>
          </ul>
        </div>
        <?php if (empty($_SESSION['user'])): ?>
      <div class="form-check form-switch me-3">
        <input class="form-check-input" type="checkbox" id="darkSwitch">
        <label class="form-check-label text-white" for="darkSwitch">ليلي</label>
      </div>
          <a class="btn btn-light me-2" href="<?= $baseUrl ?>/login"><?= App\Core\Lang::t('nav.login') ?></a>
          <a class="btn btn-outline-light" href="<?= $baseUrl ?>/register"><?= App\Core\Lang::t('nav.register') ?></a>
        <?php else: ?>
          <form method="post" action="<?= $baseUrl ?>/logout">
            <button class="btn btn-light"><?= App\Core\Lang::t('nav.logout') ?></button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<main class="container py-4">
  <?php include __DIR__ . '/../' . $template . '.php'; ?>
</main>
<footer class="app-footer py-4">
  <div class="container d-flex justify-content-between">
    <span>&copy; <?= date('Y') ?> <?= App\Core\Lang::t('app.name') ?></span>

  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Simple toast/flash system if we set window.__flash
(function(){
  if (window.__flash){
    const toast = document.createElement('div');
    toast.style.position = 'fixed'; toast.style.right = '16px'; toast.style.bottom = '16px';
    toast.className = 'alert alert-success shadow';
    toast.textContent = window.__flash;
    document.body.appendChild(toast);
    setTimeout(()=> toast.remove(), 3500);
  }
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
// Dark mode toggle persisting in localStorage
(function(){
  const key = 'doctorna_dark';
  const apply = () => {
    const on = localStorage.getItem(key)==='1';
    document.documentElement.classList.toggle('dark', on);
  };
  apply();
  const sw = document.getElementById('darkSwitch');
  if (sw){
    sw.checked = document.documentElement.classList.contains('dark');
    sw.addEventListener('change', ()=>{
      localStorage.setItem(key, sw.checked?'1':'0');
      apply();
    });
  }
})();
</script>
<script>
// auto-init flatpickr on inputs if any (fallback in RTL fine)
(function(){
  if (window.flatpickr){
    document.querySelectorAll('input[type="date"],input[type="time"],input[type="datetime-local"]').forEach(inp=>{
      try { flatpickr(inp, { enableTime: inp.type!=='date', dateFormat: inp.type==='date'?'Y-m-d':'Y-m-d H:i', time_24hr: true }); } catch(e){}
    });
  }
})();
</script>
</body>
</html>

