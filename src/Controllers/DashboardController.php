<?php
namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function home(): void
    {
        $this->view('home', ['title' => 'Welcome to Tabeebna']);
    }

    public function index(): void
    {
        $this->requireAuth(['super_admin', 'doctor', 'patient']);
        $role = $_SESSION['user']['role'];
        if ($role === 'super_admin') {
            $this->response->redirect($this->request->baseUrl() . '/dashboard/admin');
        } elseif ($role === 'doctor') {
            $this->response->redirect($this->request->baseUrl() . '/dashboard/doctor');
        } else {
            $this->response->redirect($this->request->baseUrl() . '/dashboard/patient');
        }
    }

    public function admin(): void
    {
        $this->requireAuth(['super_admin']);
        $this->view('dashboards/admin', ['title' => 'Admin Dashboard']);
    }

    public function doctor(): void
    {
        $this->requireAuth(['doctor']);
        $this->view('dashboards/doctor', ['title' => 'Doctor Dashboard']);
    }

    public function patient(): void
    {
        $this->requireAuth(['patient']);
        $this->view('dashboards/patient', ['title' => 'Patient Dashboard']);
    }
}

