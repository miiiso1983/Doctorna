<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card">
      <div class="card-body p-4 p-lg-5">
        <h2 class="mb-3">Welcome back</h2>
        <p class="text-muted">Sign in to manage your appointments.</p>
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?= App\Core\e($error) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= $baseUrl ?>/login">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button class="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>

