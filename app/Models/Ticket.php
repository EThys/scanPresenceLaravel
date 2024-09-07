<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $primaryKey = "TicketId";
    public $timestamps = false;
    public $table="TTickets";

    protected $fillable = [
        "serie",
        "name",
        "price",
        "barcode",
        "qrcode",
        "eventName",
        "eventAddress",
        "eventDate",
        "eventEndDate",
        "eventCurrency",
        "eventId",
    ];
}
