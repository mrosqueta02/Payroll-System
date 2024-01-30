<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timekeeping extends Model
{
    use HasFactory;
    protected $fillable = ['EmployeeID', 'EmployeeName', 'TimeIn', 'TimeOut','LateArrival', 'Overtime'];
}
