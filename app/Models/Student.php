<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = "Student";
    protected $primaryKey = 'studentid';
    public $timestamps = false;

    //protected $fillable = [];
    protected $guarded = [];
    protected $hidden = [];
    protected $dates = [
        'entrydate',
    ];
    protected $casts = [
        'bitspec' => 'integer',
    ];

    public function getFullnameAttribute()
    {
        return $this->st_lastname . ", " . $this->st_firstname;
    }
}
