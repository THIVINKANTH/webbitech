<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mydetails extends Model
{
    use HasFactory;
    protected $table = "mydetails";
    protected $fillable = ['name','password','email','photo'];
}
