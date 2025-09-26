<div class="d-flex justify-content-between align-items-center mb-3">
  <h2><?= App\Core\Lang::t('doctor.availability') ?></h2>
</div>
<?php if (!empty($_SESSION['flash'])): ?>
  <div class="alert alert-info"><?= App\Core\e($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
<?php endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="alert alert-danger"><?= App\Core\e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div>
<?php endif; ?>


<div class="card mb-3">
  <div class="card-body">
    <h6><?= App\Core\Lang::t('doctor.slot.add') ?></h6>
    <form method="post" action="<?= $baseUrl ?>/doctor/slots" class="row g-2">
      <input type="hidden" name="csrf" value="<?= App\Core\csrf_token() ?>">
      <div class="col-md-4">
        <input class="form-control" type="datetime-local" name="starts_at" required>
      </div>
      <div class="col-md-4">
        <input class="form-control" type="datetime-local" name="ends_at" required>
      </div>
      <div class="col-md-4">
        <button class="btn btn-primary"><?= App\Core\Lang::t('doctor.slot.add_btn') ?></button>
      </div>
    </form>
  </div>
</div>

<div class="card mb-3">
  <div class="card-body">
    <h6><?= App\Core\Lang::t('doctor.slot.recurring') ?></h6>
    <form method="post" action="<?= $baseUrl ?>/doctor/slots/recurring" class="row g-2">
      <input type="hidden" name="csrf" value="<?= App\Core\csrf_token() ?>">
      <div class="col-md-3"><input type="date" class="form-control" name="start_date" required></div>
      <div class="col-md-3"><input type="date" class="form-control" name="end_date" required></div>
      <div class="col-md-6">
        <div class="d-flex flex-wrap gap-2">
          <?php foreach ([["sun","Sun"],["mon","Mon"],["tue","Tue"],["wed","Wed"],["thu","Thu"],["fri","Fri"],["sat","Sat"]] as $d): ?>
            <label class="form-check">
              <input class="form-check-input" type="checkbox" name="days[]" value="<?= $d[0] ?>"> <span class="form-check-label"><?= $d[1] ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-md-3"><input type="time" class="form-control" name="start_time" value="09:00" required></div>
      <div class="col-md-3"><input type="time" class="form-control" name="end_time" value="17:00" required></div>
      <div class="col-md-3"><input type="number" class="form-control" name="slot_minutes" min="5" step="5" value="30" required></div>
      <div class="col-md-3"><button class="btn btn-success w-100"><?= App\Core\Lang::t('doctor.slot.recurring') ?></button></div>
    </form>
  </div>
</div>

<div class="card mb-3">
  <div class="card-body">
    <h6><?= App\Core\Lang::t('doctor.slot.delete_range') ?></h6>
    <form method="post" action="<?= $baseUrl ?>/doctor/slots/delete-range" class="row g-2">
      <input type="hidden" name="csrf" value="<?= App\Core\csrf_token() ?>">
      <div class="col-md-3"><input type="date" class="form-control" name="date_from" required></div>
      <div class="col-md-3"><input type="date" class="form-control" name="date_to" required></div>
      <div class="col-md-3"><button class="btn btn-outline-danger w-100"><?= App\Core\Lang::t('delete') ?></button></div>
    </form>
  </div>
</div>

      </div>
    </form>
  </div>
</div>
<div class="card">
  <div class="card-body">
    <h6><?= App\Core\Lang::t('doctor.availability') ?></h6>
    <table class="table table-sm">
      <thead><tr><th><?= App\Core\Lang::t('table.start') ?></th><th><?= App\Core\Lang::t('table.end') ?></th><th><?= App\Core\Lang::t('table.status') ?></th><th></th></tr></thead>
      <tbody>
        <?php foreach ($slots as $s): ?>
          <tr>
            <td><?= App\Core\e($s['starts_at']) ?></td>
            <td><?= App\Core\e($s['ends_at']) ?></td>
            <td><?php if ($s['is_booked']): ?><span class="badge bg-success"><?= App\Core\Lang::t('badge.booked') ?></span><?php else: ?><span class="badge bg-secondary"><?= App\Core\Lang::t('badge.available') ?></span><?php endif; ?></td>
            <td class="text-end">
              <?php if (!$s['is_booked']): ?>
              <form method="post" action="<?= $baseUrl ?>/doctor/slots/delete" class="d-inline" onsubmit="return confirm('<?= App\Core\Lang::t('confirm.delete_slot') ?>')">
                <input type="hidden" name="csrf" value="<?= App\Core\csrf_token() ?>">
                <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                <button class="btn btn-sm btn-outline-danger"><?= App\Core\Lang::t('delete') ?></button>
              </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
<script>
(function(){
  const formSingle = document.querySelector('form[action$="/doctor/slots"]');
  if (formSingle){
    formSingle.addEventListener('submit', function(e){
      const s = formSingle.querySelector('[name="starts_at"]').value;
      const eVal = formSingle.querySelector('[name="ends_at"]').value;
      if (!s || !eVal) return;
      const sd = new Date(s), ed = new Date(eVal);
      if (isNaN(sd.getTime()) || isNaN(ed.getTime())){ e.preventDefault(); alert('<?= App\\Core\\Lang::t('invalid.datetime') ?>'); return; }
      if (ed <= sd){ e.preventDefault(); alert('End time must be after start time'); }
    });
  }
  const formRecurring = document.querySelector('form[action$="/doctor/slots/recurring"]');
  if (formRecurring){
    formRecurring.addEventListener('submit', function(e){
      const sd = formRecurring.querySelector('[name="start_date"]').value;
      const ed = formRecurring.querySelector('[name="end_date"]').value;
      const st = formRecurring.querySelector('[name="start_time"]').value;
      const et = formRecurring.querySelector('[name="end_time"]').value;
      const dur = parseInt(formRecurring.querySelector('[name="slot_minutes"]').value || '0', 10);
      const days = formRecurring.querySelectorAll('input[name="days[]"]:checked');
      if (!sd || !ed){ e.preventDefault(); alert('Pick start and end dates'); return; }
      if (new Date(sd) > new Date(ed)){ e.preventDefault(); alert('End date must be after start date'); return; }
      if (!st || !et){ e.preventDefault(); alert('Pick start and end times'); return; }
      if (et <= st){ e.preventDefault(); alert('End time must be after start time'); return; }
      if (isNaN(dur) || dur < 5){ e.preventDefault(); alert('Slot length must be at least 5 minutes'); return; }
      if (days.length === 0){ e.preventDefault(); alert('Choose at least one weekday'); return; }
    });
  }
})();
</script>

      </tbody>
    </table>
  </div>
</div>

