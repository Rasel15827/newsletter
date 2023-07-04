<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'address',
        'state',
        'unique_id',
        'device',
        'unlock_status',
        'notes',
        'attachment',
        'traking_number',
        'priority',
        'requested_device',
        'current_status',
        'music_count',
        'received_via',
    ];

    protected $casts = [
        'created_at' => 'datetime:m/d/Y',
     ];

     public function dataLog()
     {
        return $this->hasMany(DataLog::class, 'data_id');
     }
}
