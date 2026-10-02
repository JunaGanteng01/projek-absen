<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/** Otorisasi berbasis role. Argumen `role:admin` menentukan role yang diterima. */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->has('user_id')) return redirect()->to('/login');
        if (! in_array(session('role'), $arguments ?? [], true)) return service('response')->setStatusCode(403)->setBody('Akses ditolak.');
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
