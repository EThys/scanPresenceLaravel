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

        // Store the uploaded file
        $path = $request->file('file')->store('uploads');

        // Load the CSV
        $csv = Reader::createFromPath(storage_path('app/' . $path), 'r');
        $csv->setHeaderOffset(0); 

        
        $stmt = (new Statement());

        $records = $stmt->process($csv);

        foreach ($records as $record) {
            Ticket::create([
                'serie' => $record['serie'],
                'name' => $record['name'],
                'price' => $record['price'],
                'barcode' => $record['barcode'],
                'qrcode' => $record['qrcode'],
                'eventName' => $record['eventName'],
                'eventAddress' => $record['eventAddress'],
                'eventDate' => $record['eventDate'],
                'eventEndDate' => $record['eventEndDate'],
                'eventCurrency' => $record['eventCurrency'],
                'eventId' => $record['eventId'],
            ]);
        }

        return response()->json(['success' => 'CSV processed and data saved.']);
    }
}

