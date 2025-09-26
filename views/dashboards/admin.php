<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h2 class="mb-0">لوحة المدير</h2>
    <div class="text-muted">نظرة عامة وإدارة</div>
  </div>
</div>
<div class="row g-3 mb-3">
  <div class="col-md-3">
    <div class="card"><div class="card-body"><div class="text-muted">إجمالي المستخدمين</div><div class="h4 mb-0" id="totalUsers">...</div></div></div>
  </div>
  <div class="col-md-3">
    <div class="card"><div class="card-body"><div class="text-muted">إجمالي الأطباء</div><div class="h4 mb-0" id="totalDoctors">...</div></div></div>
  </div>
  <div class="col-md-3">
    <div class="card"><div class="card-body"><div class="text-muted">إجمالي المرضى</div><div class="h4 mb-0" id="totalPatients">...</div></div></div>
  </div>
  <div class="col-md-3">
    <div class="card"><div class="card-body"><div class="text-muted">المواعيد</div><div class="h4 mb-0" id="totalAppointments">...</div></div></div>
  </div>
</div>
<div class="row g-3">
  <div class="col-md-3">
    <a class="text-decoration-none" href="<?= $baseUrl ?>/admin/users">
      <div class="card h-100"><div class="card-body"><h5>المستخدمون</h5><p class="text-muted mb-0">إدارة الأطباء والمرضى.</p></div></div>
    </a>
  </div>
  <div class="col-md-3">
    <a class="text-decoration-none" href="<?= $baseUrl ?>/admin/appointments">
      <div class="card h-100"><div class="card-body"><h5>المواعيد</h5><p class="text-muted mb-0">متابعة الحجوزات والحالات.</p></div></div>
    </a>
  </div>
  <div class="col-md-3">
    <div class="card h-100"><div class="card-body"><h5>التخصصات</h5><p class="text-muted mb-0">إدارة التخصصات الطبية.</p></div></div>
  </div>
  <div class="col-md-3">
    <div class="card h-100"><div class="card-body"><h5>التقارير</h5><p class="text-muted mb-0">عرض الإحصائيات والتحليلات.</p></div></div>
  </div>
</div>
<script>
const baseUrl = '<?= $baseUrl ?>';
(async function(){
  const res = await fetch(baseUrl + '/api/admin/stats');
  const json = await res.json();
  document.getElementById('totalUsers').textContent = json.users;
  document.getElementById('totalDoctors').textContent = json.doctors;
  document.getElementById('totalPatients').textContent = json.patients;
  document.getElementById('totalAppointments').textContent = json.appointments;
})();
</script>

