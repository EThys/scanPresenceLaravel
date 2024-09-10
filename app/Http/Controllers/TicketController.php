<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\PresenceHistory;

class TicketController extends Controller
{
    public function store(Request $request)
{
    // Valider les données entrées par l'utilisateur
    $validatedData = $request->validate([
        'serie' => 'nullable|string',
        'name' => 'nullable|string',
        'price' => 'nullable|numeric',
        'barcode' => 'nullable|string',
        'qrcode' => 'nullable|string',
        'eventName' => 'nullable|string',
        'eventAddress' => 'nullable|string',
        'eventDate' => 'nullable|date',
        'eventEndDate' => 'nullable|date',
        'eventCurrency' => 'nullable|string|max:3',
        'eventId' => 'nullable|integer',
        'status' => 'nullable|string|in:0,1',
    ]);

    // Créer un nouvel événement
    $event = Ticket::create($validatedData);

    // Retourner une réponse JSON avec les données de l'événement créé
    return response()->json([
        'status'=>200,
        'message'=>'Reussie',
        'event'=>$event
    ],200);
}

public function scan(Request $request) {
  }
  public function scanBest(Request $request) {
    $validated = $request->validate(['qrcode' => 'required|string']);
    $qrcode = $validated['qrcode'];
    $ticket = Ticket::where('qrcode', $qrcode)->first();

    if (!$ticket) {
        return response()->json(['message' => 'no'], 404);
    }
    $todayFalse=Carbon::create('2024-09-16')->format('Y-m-d');
    $today = now()->format('Y-m-d');
    if ($ticket->last_reset_date !== $today) {
        // Réinitialiser le statut du ticket spécifique
        $ticket->status = 0;
        $ticket->last_reset_date = $today;
        $ticket->save();
    }

    // Vérifiez si le ticket a déjà été scanné
    if ($ticket->status == 1) {
        return response()->json(['message' => 'noscan'], 400);
    }

    $ticket->status = 1;
    $ticket->save();


    PresenceHistory::create([
        'TicketId' => $ticket->TicketId,
        'scan_date' => now(),
        'status' => 1,
    ]);

    return response()->json(['message' => 'scan'], 200);
}
}