<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h2 class="mb-0"><?= App\Core\Lang::t('patient.title') ?></h2>
    <div class="text-muted"><?= App\Core\Lang::t('patient.subtitle') ?></div>
  </div>
</div>
<div class="row g-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h5><?= App\Core\Lang::t('ai.suggestion.title') ?></h5>
        <textarea id="symptoms" class="form-control" rows="3" placeholder="<?= App\Core\Lang::t('ai.suggestion.placeholder') ?>"></textarea>
        <button id="suggestBtn" class="btn btn-primary mt-2"><?= App\Core\Lang::t('ai.suggestion.button') ?></button>
        <div id="suggestion" class="mt-2"></div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h5><?= App\Core\Lang::t('nearby.title') ?></h5>
        <button id="nearbyBtn" class="btn btn-outline-primary"><?= App\Core\Lang::t('nearby.use_location') ?></button>
        <div id="map" style="height:300px" class="mt-2 border rounded"></div>
        <ul id="doctors" class="mt-2 list-group"></ul>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const baseUrl = '<?= $baseUrl ?>';
let map, markersLayer;
function ensureMap(lat, lng){
  if (!map){
    map = L.map('map').setView([lat, lng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
    markersLayer = L.layerGroup().addTo(map);
  } else {
    map.setView([lat, lng], 13);
    markersLayer.clearLayers();
  }
}

document.getElementById('suggestBtn').addEventListener('click', async () => {
  const symptoms = document.getElementById('symptoms').value;
  const res = await fetch(baseUrl + '/api/recommendations/specialization', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ symptoms })
  });
  const json = await res.json();
  document.getElementById('suggestion').innerHTML = '<?= App\Core\Lang::t('ai.suggestion.result') ?>'.replace('{name}', (json.suggestion?.name || 'طبيب عام'));
});

document.getElementById('nearbyBtn').addEventListener('click', () => {
  if (!navigator.geolocation) return alert('<?= App\Core\Lang::t('geo.unsupported') ?>');
  navigator.geolocation.getCurrentPosition(async (pos) => {
    const { latitude, longitude } = pos.coords;
    ensureMap(latitude, longitude);
    const res = await fetch(baseUrl + '/api/doctors/nearby?lat=' + latitude + '&lng=' + longitude + '&radius_km=25');
    const json = await res.json();
    const ul = document.getElementById('doctors');
    ul.innerHTML = '';
    markersLayer.clearLayers();
    json.data.forEach(async (d) => {
      const li = document.createElement('li');
      li.className = 'list-group-item';
      li.innerHTML = `<div class=\"d-flex justify-content-between align-items-center\">`+
                     `<span class=\"fw-semibold\">${d.name}</span>`+
                     `<span class=\"text-muted small\">${d.specialization || '<?= App\Core\Lang::t('not_available') ?>'} • ${Number(d.distance_km).toFixed(1)} km</span>`+
                     `</div>`+
                     `<div class=\"mt-2\"><select class=\"form-select form-select-sm slot-select\"><option>تحميل المواعيد...</option></select>`+
                     `<button class=\"btn btn-sm btn-success ms-2 book-btn\">احجز</button></div>`;
      ul.appendChild(li);
      // load slots
      const sel = li.querySelector('.slot-select');
      const resSlots = await fetch(baseUrl + '/api/slots?doctor_id=' + d.id);
      const jSlots = await resSlots.json();
      sel.innerHTML = '';
      if (!jSlots.data.length){ sel.innerHTML = '<option disabled>لا يوجد مواعيد</option>'; }
      jSlots.data.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id; opt.textContent = `${s.starts_at} - ${s.ends_at}`;
        sel.appendChild(opt);
      });
      li.querySelector('.book-btn').addEventListener('click', async () => {
        const slotId = sel.value;
        if (!slotId) return alert('<?= App\Core\Lang::t('select.slot') ?>');
        const res2 = await fetch(baseUrl + '/api/patient/appointments', {
          method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ doctor_id: d.id, slot_id: slotId })
        });
        const j2 = await res2.json();
        alert(j2.message || '<?= App\Core\Lang::t('request.sent') ?>');
      });
      if (d.latitude && d.longitude){
        L.marker([d.latitude, d.longitude]).addTo(markersLayer).bindPopup(d.name + ' - ' + (d.specialization || 'N/A'));
      }
    });
  });
});
</script>

