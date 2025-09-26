<div class="row justify-content-center">
  <div class="col-md-8 col-lg-7">
    <div class="card">
      <div class="card-body p-4 p-lg-5">
        <h2 class="mb-3">Create your account</h2>
        <p class="text-muted">Join Tabeebna to book and manage appointments.</p>
        <form method="post" action="<?= $baseUrl ?>/register">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-2 mb-3">
              <label class="form-label">Mobile (with country code)</label>
              <input type="tel" name="phone" class="form-control" placeholder="e.g. +201234567890">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Register as</label>
              <select name="role" class="form-select">
                <option value="patient">Patient</option>
                <option value="doctor">Doctor</option>
              </select>
            </div>
          </div>
          <button class="btn btn-primary w-100">Create account</button>
        </form>
      </div>
    </div>
  </div>
</div>

