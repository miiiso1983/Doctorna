<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h2 class="mb-0"><?= App\Core\Lang::t('doctor.dashboard.title') ?></h2>
    <div class="text-muted"><?= App\Core\Lang::t('doctor.dashboard.subtitle') ?></div>
  </div>
  <div>
    <a class="btn btn-primary" href="<?= $baseUrl ?>/doctor/slots"><?= App\Core\Lang::t('doctor.manage_availability') ?></a>
  </div>
</div>
<div class="card">
  <div class="card-body">
    <h5><?= App\Core\Lang::t('doctor.your_appointments') ?></h5>
    <ul id="appointments" class="list-group"></ul>
  </div>
</div>
<script>
const baseUrl = '<?= $baseUrl ?>';
async function loadAppointments(){
  const res = await fetch(baseUrl + '/api/doctor/appointments');
  const json = await res.json();
  const ul = document.getElementById('appointments');
  ul.innerHTML = '';
  json.data.forEach(a => {
    const li = document.createElement('li');
    li.className = 'list-group-item d-flex justify-content-between align-items-center';
    li.innerHTML = `<div>
        <div class=\"fw-semibold\">${a.patient_name}</div>
        <div class=\"text-muted small\">${a.appointment_date}</div>
      </div>
      <div>
        <span class=\"badge me-2\" id=\"st-${a.id}\"></span>
        <button class=\"btn btn-sm btn-success me-1\" data-s=\"accepted\">قبول</button>
        <button class=\"btn btn-sm btn-danger\" data-s=\"rejected\">رفض</button>
      </div>`;
    li.querySelectorAll('button').forEach(btn => btn.addEventListener('click', async () => {
      const status = btn.getAttribute('data-s');
      await fetch(baseUrl + '/api/doctor/appointments/status', {
        method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ id: a.id, status })
      });
      loadAppointments();
    }));
    ul.appendChild(li);
  });
  // apply colored badge + arabic label
  const map = {
    pending: { t: 'معلّق', c: 'bg-warning text-dark' },
    accepted: { t: 'مقبول', c: 'bg-success' },
    rejected: { t: 'مرفوض', c: 'bg-danger' },
    cancelled: { t: 'ملغي', c: 'bg-secondary' },
    completed: { t: 'مكتمل', c: 'bg-primary' },
  };
  json.data.forEach(a => {
    const st = (a.status||'').toLowerCase();
    const m = map[st] || { t: st, c: 'bg-secondary' };
    const el = document.getElementById('st-'+a.id);
    if (el){ el.classList.add('badge'); el.classList.add(...m.c.split(' ')); el.textContent = m.t; }
  });
}
loadAppointments();
</script>

