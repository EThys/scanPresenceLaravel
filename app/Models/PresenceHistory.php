<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenceHistory extends Model
{
    protected $primaryKey = "TPresenceId";
    public $timestamps = false;
    public $table="TPresenceHistories";
    use HasFactory;protected $fillable = [
        "PresenceId",
        "TicketId",
        "scan_date",
        "status"
    ];
}
