<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = "Staff";
    protected $primaryKey = 'staffid';
    public $timestamps = false;
    protected $casts = [
        'bitspec' => 'integer',
    ];
    //protected $fillable = [];
    protected $guarded = [];

    public function getFullnameAttribute()
    {
        return $this->lastname . ", " . $this->firstname;
    }
}
