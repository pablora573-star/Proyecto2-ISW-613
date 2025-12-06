<?php

namespace App\Controllers;

class Pasajero extends BaseController
{
    public function index(): string
    {
        return view('/pasajeros/registration_pasajero');
    }

    public function registro()
    {
        return view('/pasajeros/registration_pasajero');
    }
}