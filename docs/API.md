## Tabeebna API (Draft)

Base URL: {BASE_URL}

- GET /api/specializations
  - 200: { data: [ { id, name } ] }

- GET /api/doctors/nearby?lat=..&lng=..&radius_km=25
  - 200: { data: [ { id, name, specialization, latitude, longitude, distance_km } ] }

- POST /api/recommendations/specialization
  - Body: x-www-form-urlencoded { symptoms: string }
  - 200: { suggestion: { id|null, name } }

- POST /api/patient/appointments
  - Auth: patient session
  - Body: x-www-form-urlencoded { doctor_id: int, appointment_date: "YYYY-MM-DD HH:MM", notes? }
  - 200: { message }

