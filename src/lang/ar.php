<?php
return [
  'app.name' => 'طبيبنا',
  'nav.home' => 'الرئيسية',
  'nav.dashboard' => 'لوحتي',
  'nav.login' => 'دخول',
  'nav.register' => 'تسجيل',
  'nav.logout' => 'خروج',
  'nav.language' => 'اللغة',
  'dark' => 'ليلي',
  

  // Admin Appointments
  'admin.appointments' => 'المواعيد',
  'export.csv' => 'تصدير CSV',
  'all.statuses' => 'كل الحالات',
  'all.doctors' => 'كل الأطباء',
  'all.patients' => 'كل المرضى',
  'from' => 'من',
  'to' => 'إلى',
  'filter' => 'تصفية',

  'id' => 'م',
  'date' => 'التاريخ',
  'doctor' => 'الطبيب',
  'patient' => 'المريض',
  'status' => 'الحالة',
  'actions' => 'إجراءات',

  'status.pending' => 'معلّق',
  'status.accepted' => 'مقبول',
  'status.rejected' => 'مرفوض',
  'status.cancelled' => 'ملغي',
  'status.completed' => 'مكتمل',

  'action.accept' => 'قبول',
  'action.reject' => 'رفض',
  'action.cancel' => 'إلغاء',
  'action.complete' => 'إتمام',

  'confirm.status' => 'تأكيد الحالة: {status}؟',

  // Dashboards
  'admin.dashboard.title' => 'لوحة المدير',
  'admin.dashboard.subtitle' => 'نظرة عامة وإدارة',
  'doctor.dashboard.title' => 'لوحة الطبيب',
  'doctor.dashboard.subtitle' => 'مواعيد اليوم والقادم',
  'doctor.manage_availability' => 'إدارة التوفر',
  'doctor.your_appointments' => 'مواعيدك',
  'patient.title' => 'المريض',
  'patient.subtitle' => 'اعثر على الأطباء واحجز موعداً',

  // Patient widgets
  'ai.suggestion.title' => 'اقتراح التخصص (ذكاء اصطناعي)',
  'ai.suggestion.placeholder' => 'صف الأعراض...',
  'ai.suggestion.button' => 'اقترح تخصصاً',
  'ai.suggestion.result' => 'الاقتراح: <b>{name}</b>',
  'nearby.title' => 'اعثر على الأطباء القريبين',
  'nearby.use_location' => 'استخدم موقعي',
  'nearby.loading_slots' => 'تحميل المواعيد...',
  'nearby.no_slots' => 'لا يوجد مواعيد',
  'nearby.book' => 'احجز',
  'km' => 'كم',
  'not_available' => 'غير متاح',
  'geo.unsupported' => 'الموقع الجغرافي غير مدعوم في متصفحك',
  'select.slot' => 'الرجاء اختيار فترة زمنية',
  'request.sent' => 'تم إرسال الطلب',

  // Doctor slots page
  'doctor.availability' => 'توفرّي',
  'doctor.slot.add' => 'إضافة فترة زمنية',
  'doctor.slot.add_btn' => 'إضافة',
  'doctor.slot.recurring' => 'إنشاء فترات متكررة',
  'doctor.slot.delete_range' => 'حذف الفترات غير المحجوزة',
  'table.start' => 'البداية',
  'table.end' => 'النهاية',
  'table.status' => 'الحالة',
  'badge.booked' => 'محجوز',
  'badge.available' => 'متاح',
  'delete' => 'حذف',
  'confirm.delete_slot' => 'حذف هذه الفترة؟',

  // Charts
  'chart.appts_by_status' => 'المواعيد حسب الحالة',

  // Emails
  'mail.appt.new.subject' => 'طلب موعد جديد',
  'mail.appt.new.body.doctor' => 'دكتور {doctor},\nتم طلب موعد جديد بتاريخ {date}.',
  'mail.appt.new.body.patient' => 'عزيزي {patient},\nتم إرسال طلب موعد بتاريخ {date}.',

  'mail.appt.status.accepted.subject' => 'تم قبول الموعد',
  'mail.appt.status.accepted.body' => 'تم قبول موعدك بتاريخ {date}.',
  'mail.appt.status.rejected.subject' => 'تم رفض الموعد',
  'mail.appt.status.rejected.body' => 'تم رفض موعدك بتاريخ {date}.',
  'mail.appt.status.cancelled.subject' => 'تم إلغاء الموعد',
  'mail.appt.status.cancelled.body' => 'تم إلغاء موعدك بتاريخ {date}.',
  'mail.appt.status.completed.subject' => 'تم إتمام الموعد',
  'mail.appt.status.completed.body' => 'تم إتمام موعدك بتاريخ {date}.',

  // WhatsApp
  'wa.appt.accepted' => 'تم قبول موعدك بتاريخ {date}',
  'wa.appt.rejected' => 'تم رفض موعدك بتاريخ {date}',
  'wa.appt.cancelled' => 'تم إلغاء موعدك بتاريخ {date}',
  'wa.appt.completed' => 'تم إتمام موعدك بتاريخ {date}',
];

