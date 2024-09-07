<?php

namespace App\Http\Controllers;

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

    $validated = $request->validate(['qrcode' => 'required|string']);
    $qrcode = $validated['qrcode'];
  
    $ticket = Ticket::where('qrcode', $qrcode)->first();
  
    if(!$ticket) {
      return response()->json(['message' => 'no'], 404);
    }
    if($ticket->status == 1) {
      return response()->json(['message' => 'noscan'], 400);
    }
  
    $ticket->status = 1;
    $ticket->save();
  
    return response()->json(['message' => 'scan'], 200);
  }
  public function scanBest(Request $request) {
    $validated = $request->validate(['qrcode' => 'required|string']);
    $qrcode = $validated['qrcode'];
    $ticket = Ticket::where('qrcode', $qrcode)->first();

    if (!$ticket) {
        return response()->json(['message' => 'no'], 404);
    }

    // Vérifiez si la date de réinitialisation est différente d'aujourd'hui
    $today = now()->format('Y-m-d');
    if ($ticket->last_reset_date !== $today) {
        // Réinitialiser tous les statuts à 0
        Ticket::query()->update(['status' => 0, 'last_reset_date' => $today]);
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
