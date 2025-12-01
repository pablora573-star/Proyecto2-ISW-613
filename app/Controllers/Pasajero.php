<?php

namespace App\Controllers;

class Pasajero extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
}