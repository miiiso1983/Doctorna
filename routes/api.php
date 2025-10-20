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


// -------- v1 namespaced endpoints for mobile clients --------
use App\Controllers\Api\AuthController as ApiAuth;

$router->post('/api/v1/auth/login', [ApiAuth::class, 'login']);
$router->post('/api/v1/auth/refresh', [ApiAuth::class, 'refresh']);
$router->post('/api/v1/auth/logout', [ApiAuth::class, 'logout']);

$router->get('/api/v1/specializations', [SpecializationController::class, 'index']);
$router->get('/api/v1/doctors/nearby', [DoctorController::class, 'nearby']);
$router->post('/api/v1/ai/suggest', [RecommendationController::class, 'suggestSpecialization']);
$router->get('/api/v1/slots', [SlotController::class, 'availableByDoctor']);
use App\Controllers\Api\AppointmentController as ApiPatientAppointments;
use App\Controllers\Api\DoctorAppointmentsController as ApiDoctorAppointments;

$router->get('/api/v1/appointments', [ApiPatientAppointments::class, 'index']);
$router->post('/api/v1/patient/appointments', [ApiPatientAppointments::class, 'create']);
$router->get('/api/v1/doctor/appointments', [ApiDoctorAppointments::class, 'index']);
$router->post('/api/v1/doctor/appointments/status', [ApiDoctorAppointments::class, 'updateStatus']);
$router->get('/api/v1/admin/stats', [AdminController::class, 'stats']);

