<?php
use function App\Core\csrf_token;
use function App\Core\e;
$baseUrl = $baseUrl ?? $this->request->baseUrl();
$filters = $filters ?? [];
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2><?= App\Core\Lang::t('admin.appointments') ?></h2>
  <div>
    <a class="btn btn-outline-secondary" href="<?= $baseUrl ?>/admin/appointments/export">تصدير CSV</a>
  </div>
</div>

<div class="card mb-3">
  <div class="card-body">
    <form class="row g-2" method="get" action="<?= $baseUrl ?>/admin/appointments">
      <div class="col-md-2">
        <select class="form-select" name="status">
          <option value="">كل الحالات</option>
          <?php foreach (['pending','accepted','rejected','cancelled','completed'] as $st): ?>
            <option value="<?= $st ?>" <?= ($filters['status']??'')===$st?'selected':'' ?>><?= ucfirst($st) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-select" name="doctor_id">
          <option value="0">كل الأطباء</option>
          <?php foreach ($doctors as $d): ?>
          <option value="<?= (int)$d['id'] ?>" <?= ((int)($filters['doctorId']??0)===(int)$d['id'])?'selected':'' ?>><?= e($d['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-select" name="patient_id">
          <option value="0">كل المرضى</option>
          <?php foreach ($patients as $p): ?>
          <option value="<?= (int)$p['id'] ?>" <?= ((int)($filters['patientId']??0)===(int)$p['id'])?'selected':'' ?>><?= e($p['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2"><input type="date" class="form-control" name="from" value="<?= e($filters['from']??'') ?>" placeholder="من"></div>
      <div class="col-md-2"><input type="date" class="form-control" name="to" value="<?= e($filters['to']??'') ?>" placeholder="إلى"></div>
      <div class="col-md-12 text-end">
        <button class="btn btn-primary">تصفية</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead>
          <tr>
            <th>م</th>
            <th>التاريخ</th>
            <th>الطبيب</th>
            <th>المريض</th>
            <th>الحالة</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= e($r['appointment_date']) ?></td>
            <td><?= e($r['doctor_name']) ?></td>
            <td><?= e($r['patient_name']) ?></td>
            <td>
              <?php
                $map = [
                  'pending' => ['text' => 'معلّق', 'class' => 'bg-warning text-dark'],
                  'accepted' => ['text' => 'مقبول', 'class' => 'bg-success'],
                  'rejected' => ['text' => 'مرفوض', 'class' => 'bg-danger'],
                  'cancelled' => ['text' => 'ملغي', 'class' => 'bg-secondary'],
                  'completed' => ['text' => 'مكتمل', 'class' => 'bg-primary'],
                ];
                $st = strtolower((string)$r['status']);
                $label = $map[$st]['text'] ?? $st;
                $cls = $map[$st]['class'] ?? 'bg-secondary';
              ?>
              <span class="badge <?= $cls ?>"><?= e($label) ?></span>
            </td>
            <td class="text-end">
              <form method="post" action="<?= $baseUrl ?>/admin/appointments/status" class="d-inline">
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                <div class="btn-group">
                  <?php foreach ([
                    'accepted' => 'قبول',
                    'rejected' => 'رفض',
                    'cancelled' => 'إلغاء',
                    'completed' => 'إتمام'
                  ] as $k => $txt): ?>
                    <button name="status" value="<?= $k ?>" class="btn btn-sm btn-outline-primary" onclick="return confirm('تأكيد الحالة: <?= $txt ?>؟')"><?= $txt ?></button>
                  <?php endforeach; ?>
                </div>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <hr>
    <h6>Overview</h6>
    <div>
      <canvas id="apptsChart" height="120"></canvas>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function(){
  // Basic client-side chart from table rows (count by status)
  const rows = Array.from(document.querySelectorAll('tbody tr'));
  const counts = {};
  rows.forEach(tr => {
    const status = tr.querySelector('td:nth-child(5)').innerText.trim();
    counts[status] = (counts[status]||0)+1;
  });
  const labels = Object.keys(counts);
  const data = Object.values(counts);
  new Chart(document.getElementById('apptsChart'), {
    type: 'bar',
    data: { labels, datasets: [{ label: 'Appointments by status', data, backgroundColor: '#0d6efd' }] },
    options: { scales: { y: { beginAtZero: true, precision:0 } } }
  });
})();
</script>

