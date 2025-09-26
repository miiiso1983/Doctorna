<?php
use App\Core\Router;
use App\Controllers\Api\SpecializationController;
use App\Controllers\Api\DoctorController;
use App\Controllers\Api\RecommendationController;
use App\Controllers\Api\AdminController;
use App\Controllers\Api\SlotController;
use App\Controllers\Patient\AppointmentController as PatientAppointments;
use App\Controllers\Doctor\AppointmentController as DoctorAppointments;

/** @var Router $router */

$router->get('/api/specializations', [SpecializationController::class, 'index']);
$router->get('/api/doctors/nearby', [DoctorController::class, 'nearby']);
$router->post('/api/recommendations/specialization', [RecommendationController::class, 'suggestSpecialization']);
$router->get('/api/slots', [SlotController::class, 'availableByDoctor']);
$router->post('/api/patient/appointments', [PatientAppointments::class, 'create']);
$router->get('/api/doctor/appointments', [DoctorAppointments::class, 'index']);
$router->post('/api/doctor/appointments/status', [DoctorAppointments::class, 'updateStatus']);
$router->get('/api/admin/stats', [AdminController::class, 'stats']);

