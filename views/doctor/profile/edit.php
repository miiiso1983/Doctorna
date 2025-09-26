<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>My Profile</h2>
</div>
<div class="card">
  <div class="card-body">
    <form method="post" action="<?= $baseUrl ?>/doctor/profile">
      <input type="hidden" name="csrf" value="<?= App\Core\csrf_token() ?>">
      <div class="mb-3">
        <label class="form-label">Specialization</label>
        <select class="form-select" name="specialization_id">
          <option value="">-- Select --</option>
          <?php foreach ($specializations as $s): ?>
            <option value="<?= (int)$s['id'] ?>" <?= ((int)($doctor['specialization_id'] ?? 0) === (int)$s['id']) ? 'selected' : '' ?>>
              <?= App\Core\e($s['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Bio</label>
        <textarea class="form-control" name="bio" rows="4"><?= App\Core\e($doctor['bio'] ?? '') ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Working Hours (JSON)</label>
        <textarea class="form-control" name="working_hours" rows="3" placeholder='{"mon":["09:00-12:00","14:00-17:00"]}'><?= App\Core\e($doctor['working_hours'] ?? '') ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Enable WhatsApp confirmations</label>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" name="whatsapp_enabled" value="1" <?= !empty($doctor['whatsapp_enabled'])?'checked':'' ?>>
          <label class="form-check-label">Send confirmation via WhatsApp to patient when you accept</label>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">WhatsApp From Phone ID</label>
        <input type="text" class="form-control" name="whatsapp_from_phone_id" value="<?= App\Core\e($doctor['whatsapp_from_phone_id'] ?? '') ?>" placeholder="e.g. 14157312345">
        <div class="form-text">ID or number used by your WhatsApp Business API (configure in settings).</div>
      </div>

      <button class="btn btn-primary">Save</button>
    </form>
  </div>
</div>

