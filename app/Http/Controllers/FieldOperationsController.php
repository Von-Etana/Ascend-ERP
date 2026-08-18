<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class FieldOperationsController extends Controller
{
    public function showPortal(Request $request): View
    {
        $dataStr = $request->query('data');
        if (!$dataStr) {
            abort(404, 'Inspection job data link is missing.');
        }

        $quote = json_decode(base64_decode($dataStr), true);
        if (!$quote || !is_array($quote)) {
            abort(404, 'Invalid inspection job reference.');
        }

        return view('public.field-portal', [
            'quote' => $quote,
        ]);
    }
}
