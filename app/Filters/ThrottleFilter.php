<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/** Rate limiter sederhana berbasis cache. Input: batas dan menit; output HTTP 429 saat terlampaui. */
class ThrottleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $limit=(int)($arguments[0]??10); $seconds=((int)($arguments[1]??1))*60;
        $key='rate_'.hash('sha256', $request->getIPAddress().'|'.$request->getUri()->getPath());
        $count=(int)(cache($key)??0)+1; cache()->save($key,$count,$seconds);
        if ($count>$limit) return service('response')->setStatusCode(429)->setJSON(['success'=>false,'message'=>'Terlalu banyak permintaan. Coba lagi sebentar.']);
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
