<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    public const STATUSES = ['new' => 'Baru', 'contacted' => 'Dihubungi', 'closed' => 'Selesai'];

    protected $guarded = ['id'];
}
