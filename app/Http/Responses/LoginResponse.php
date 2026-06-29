<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        return match ($request->user()->role) {
            'admin' => redirect()->intended('/admin/dashboard'),
            default => redirect()->intended('/karyawan/dashboard'),
        };
    }
}
