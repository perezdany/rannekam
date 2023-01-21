<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    public $timestamp = false;
    public $pseudo = "pseudo";
    public $password = "password";
    public $type = "type";
}
