<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $primaryKey = "TicketId";
    public $timestamps = false;
    public $table="ttickets";

    protected $fillable = [
        "nom",
        "postnom",
        "prenom",
        "nombre_des_personnes",
        "civilite",
        "presence"
    ];
}
