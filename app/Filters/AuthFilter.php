<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/** Memastikan session pengguna tersedia sebelum controller privat dipanggil. */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->has('user_id')) return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
