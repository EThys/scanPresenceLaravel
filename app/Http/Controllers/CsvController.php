<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Validator;

class CsvController extends Controller
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:18000',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }


        $path = $request->file('file')->store('uploads');

        $csv = Reader::createFromPath(storage_path('app/' . $path), 'r');
        $csv->setHeaderOffset(0);

        $stmt = (new Statement());
        $records = $stmt->process($csv);

        foreach ($records as $record) {
            // Vérifiez si le ticket existe déjà
            $exists = Ticket::where('nom', $record['nom'])
                            ->where('postnom', $record['postnom'])
                            ->where('prenom', $record['prenom'])
                            ->first();

            if (!$exists) {
                Ticket::create([
                    'nom' => $record['nom'],
                    'postnom' => $record['postnom'],
                    'prenom' => $record['prenom'],
                    'nombre_des_personnes' => $record['nombre_des_personnes'],
                    'civilite' => $record['civilite'],
                    'presence' => $record['presence']
                ]);
            }
        }

        return response()->json(['success' => 'CSV processed and data saved, duplicates ignored.']);
    }
}
