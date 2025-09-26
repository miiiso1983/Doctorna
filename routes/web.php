<?php
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\Admin\SpecializationController as AdminSpecializations;
use App\Controllers\Admin\UserController as AdminUsers;
use App\Controllers\Doctor\ProfileController as DoctorProfile;
use App\Controllers\Doctor\TimeSlotController as DoctorSlots;

/** @var Router $router */

$router->get('/', [DashboardController::class, 'home']);

// Auth routes
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->post('/logout', [AuthController::class, 'logout']);

// Dashboards
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/dashboard/admin', [DashboardController::class, 'admin']);
$router->get('/dashboard/doctor', [DashboardController::class, 'doctor']);
$router->get('/dashboard/patient', [DashboardController::class, 'patient']);

// Doctor profile & slots
$router->get('/doctor/profile', [DoctorProfile::class, 'edit']);
$router->post('/doctor/profile', [DoctorProfile::class, 'update']);
$router->get('/doctor/slots', [DoctorSlots::class, 'index']);
$router->post('/doctor/slots', [DoctorSlots::class, 'store']);
$router->post('/doctor/slots/recurring', [DoctorSlots::class, 'storeRecurring']);
$router->post('/doctor/slots/delete', [DoctorSlots::class, 'destroy']);
$router->post('/doctor/slots/delete-range', [DoctorSlots::class, 'destroyRange']);

// Admin: Specializations
$router->get('/admin/specializations', [AdminSpecializations::class, 'index']);
$router->post('/admin/specializations', [AdminSpecializations::class, 'store']);
$router->post('/admin/specializations/delete', [AdminSpecializations::class, 'destroy']);

// Admin: Users
$router->get('/admin/users', [AdminUsers::class, 'index']);
$router->post('/admin/users', [AdminUsers::class, 'store']);
$router->post('/admin/users/delete', [AdminUsers::class, 'destroy']);

// Admin: Appointments
$router->get('/admin/appointments', [App\Controllers\Admin\AppointmentController::class, 'index']);
$router->post('/admin/appointments/status', [App\Controllers\Admin\AppointmentController::class, 'updateStatus']);
$router->get('/admin/appointments/export', [App\Controllers\Admin\AppointmentController::class, 'exportCsv']);

// Language switch
$router->get('/lang', [App\Controllers\LangController::class, 'switch']);

