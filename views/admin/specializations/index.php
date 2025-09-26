<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Specializations</h2>
</div>
<div class="row g-3">
  <div class="col-md-5">
    <div class="card">
      <div class="card-body">
        <h6>Add New Specialization</h6>
        <form method="post" action="<?= $baseUrl ?>/admin/specializations">
          <input type="hidden" name="csrf" value="<?= App\Core\csrf_token() ?>">
          <div class="input-group">
            <input class="form-control" name="name" placeholder="Name" required>
            <button class="btn btn-primary">Add</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card">
      <div class="card-body">
        <h6>Existing</h6>
        <table class="table table-sm">
          <thead><tr><th>ID</th><th>Name</th><th></th></tr></thead>
          <tbody>
          <?php foreach ($specializations as $s): ?>
            <tr>
              <td><?= (int)$s['id'] ?></td>
              <td><?= App\Core\e($s['name']) ?></td>
              <td class="text-end">
                <form method="post" action="<?= $baseUrl ?>/admin/specializations/delete" onsubmit="return confirm('Delete this specialization?')">
                  <input type="hidden" name="csrf" value="<?= App\Core\csrf_token() ?>">
                  <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                  <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

