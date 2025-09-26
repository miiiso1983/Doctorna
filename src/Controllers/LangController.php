<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Lang;

class LangController extends Controller
{
    public function switch(): void
    {
        $locale = (string)($this->request->get['l'] ?? 'ar');
        Lang::setLocale($locale);
        $ref = $_SERVER['HTTP_REFERER'] ?? ($this->request->baseUrl() . '/');
        $this->response->redirect($ref);
    }
}

