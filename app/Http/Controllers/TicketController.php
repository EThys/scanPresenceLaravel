<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\PresenceHistory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function getAllTickets()
    {
        $tickets = Ticket::all();
        return new TicketCollection($tickets);
    }
    public function searchTickets(Request $request)
    {
        $query = $request->input('query');

        $tickets = Ticket::where('nom', 'LIKE', "%{$query}%")
            ->orWhere('postnom', 'LIKE', "%{$query}%")
            ->orWhere('prenom', 'LIKE', "%{$query}%")
            ->orWhere('civilite', 'LIKE', "%{$query}%")
            ->get();

        return new TicketCollection($tickets);
    }
    public function updatePresence(Request $request, $id)
    {
        $request->validate([
            'presence' => 'required|boolean',
        ]);

        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        $ticket->presence = $request->input('presence');
        $ticket->save();

        return response()->json(['message' => 'Ok', 'ticket' => $ticket]);
    }
}
