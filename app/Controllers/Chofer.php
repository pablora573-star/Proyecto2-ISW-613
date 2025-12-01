<?php

namespace App\Controllers;

class Chofer extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
}