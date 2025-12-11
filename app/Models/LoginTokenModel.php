<?php
namespace App\Models;

use CodeIgniter\Model;

class LoginTokenModel extends Model
{
    protected $table = 'login_tokens';
    protected $allowedFields = ['user_id', 'token', 'used'];
}
