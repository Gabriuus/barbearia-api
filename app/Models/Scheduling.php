<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Scheduling extends Model
{
    use HasUuids;

    protected $fillable = ['client_id', 'start_date', 'end_date'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
