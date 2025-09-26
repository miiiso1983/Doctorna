<?php
return [
  'app.name' => 'Tabeebna',
  'nav.home' => 'Home',
  'nav.dashboard' => 'Dashboard',
  'nav.login' => 'Login',
  'nav.register' => 'Register',
  'nav.logout' => 'Logout',
  'nav.language' => 'Language',
  'dark' => 'Dark',
  'footer.built' => 'Built with ❤️',

  // Admin Appointments
  'admin.appointments' => 'Appointments',
  'export.csv' => 'Export CSV',
  'all.statuses' => 'All statuses',
  'all.doctors' => 'All doctors',
  'all.patients' => 'All patients',
  'from' => 'From',
  'to' => 'To',
  'filter' => 'Filter',

  'id' => 'ID',
  'date' => 'Date',
  'doctor' => 'Doctor',
  'patient' => 'Patient',
  'status' => 'Status',
  'actions' => 'Actions',

  'status.pending' => 'Pending',
  'status.accepted' => 'Accepted',
  'status.rejected' => 'Rejected',
  'status.cancelled' => 'Cancelled',
  'status.completed' => 'Completed',

  'action.accept' => 'Accept',
  'action.reject' => 'Reject',
  'action.cancel' => 'Cancel',
  'action.complete' => 'Complete',

  'confirm.status' => 'Set status to {status}?',

  // Dashboards
  'admin.dashboard.title' => 'Admin Dashboard',
  'admin.dashboard.subtitle' => 'Overview and management',
  'doctor.dashboard.title' => 'Doctor Dashboard',
  'doctor.dashboard.subtitle' => 'Today and upcoming appointments',
  'doctor.manage_availability' => 'Manage Availability',
  'doctor.your_appointments' => 'Your Appointments',
  'patient.title' => 'Patient',
  'patient.subtitle' => 'Find doctors and book a slot',

  // Patient widgets
  'ai.suggestion.title' => 'AI Specialization Suggestion',
  'ai.suggestion.placeholder' => 'Describe your symptoms...',
  'ai.suggestion.button' => 'Suggest Specialization',
  'ai.suggestion.result' => 'Suggested: <b>{name}</b>',
  'nearby.title' => 'Find Nearby Doctors',
  'nearby.use_location' => 'Use my location',
  'nearby.loading_slots' => 'Loading slots...',
  'nearby.no_slots' => 'No slots',
  'nearby.book' => 'Book',
  'km' => 'km',
  'not_available' => 'N/A',
  'geo.unsupported' => 'Geolocation not supported',
  'select.slot' => 'Select a slot',
  'request.sent' => 'Requested',

  // Doctor slots page
  'doctor.availability' => 'My Availability',
  'doctor.slot.add' => 'Add Time Slot',
  'doctor.slot.add_btn' => 'Add Slot',
  'doctor.slot.recurring' => 'Create Recurring Slots',
  'doctor.slot.delete_range' => 'Delete Unbooked Slots',
  'table.start' => 'Start',
  'table.end' => 'End',
  'table.status' => 'Status',
  'badge.booked' => 'Booked',
  'badge.available' => 'Available',
  'delete' => 'Delete',
  'confirm.delete_slot' => 'Delete this slot?',

  // Charts
  'chart.appts_by_status' => 'Appointments by status',

  // Emails
  'mail.appt.new.subject' => 'New appointment request',
  'mail.appt.new.body.doctor' => 'Dr. {doctor},\nA new appointment is requested on {date}.',
  'mail.appt.new.body.patient' => 'Dear {patient},\nYour appointment request was sent for {date}.',

  'mail.appt.status.accepted.subject' => 'Appointment accepted',
  'mail.appt.status.accepted.body' => 'Your appointment was accepted for {date}.',
  'mail.appt.status.rejected.subject' => 'Appointment rejected',
  'mail.appt.status.rejected.body' => 'Your appointment was rejected for {date}.',
  'mail.appt.status.cancelled.subject' => 'Appointment cancelled',
  'mail.appt.status.cancelled.body' => 'Your appointment was cancelled for {date}.',
  'mail.appt.status.completed.subject' => 'Appointment completed',
  'mail.appt.status.completed.body' => 'Your appointment was completed on {date}.',

  // WhatsApp
  'wa.appt.accepted' => 'Your appointment has been accepted for {date}',
  'wa.appt.rejected' => 'Your appointment has been rejected for {date}',
  'wa.appt.cancelled' => 'Your appointment has been cancelled for {date}',
  'wa.appt.completed' => 'Your appointment has been completed on {date}',
];

