<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Users</h2>
</div>
<div class="row g-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h6>Create User</h6>
        <form method="post" action="<?= $baseUrl ?>/admin/users">
          <input type="hidden" name="csrf" value="<?= App\Core\csrf_token() ?>">
          <div class="row g-2">
            <div class="col-md-4"><input class="form-control" name="name" placeholder="Name" required></div>
            <div class="col-md-4"><input class="form-control" name="email" type="email" placeholder="Email" required></div>
            <div class="col-md-2">
              <select class="form-select" name="role">
                <option value="patient">Patient</option>
                <option value="doctor">Doctor</option>
                <option value="super_admin">Super Admin</option>
              </select>
            </div>
            <div class="col-md-2"><input class="form-control" name="password" type="password" placeholder="Password" required></div>
            <div class="col-12"><button class="btn btn-primary">Create</button></div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h6>All Users</h6>
        <table class="table table-sm">
          <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th></th></tr></thead>
          <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= (int)$u['id'] ?></td>
              <td><?= App\Core\e($u['name']) ?></td>
              <td><?= App\Core\e($u['email']) ?></td>
              <td><span class="badge bg-secondary"><?= App\Core\e($u['role']) ?></span></td>
              <td class="text-end">
                <?php if ((int)$u['id'] !== (int)($_SESSION['user']['id'] ?? 0)): ?>
                <form method="post" action="<?= $baseUrl ?>/admin/users/delete" onsubmit="return confirm('Delete this user?')" class="d-inline">
                  <input type="hidden" name="csrf" value="<?= App\Core\csrf_token() ?>">
                  <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                  <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

