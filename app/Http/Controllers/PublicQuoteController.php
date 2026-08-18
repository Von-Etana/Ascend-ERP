<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicQuoteController extends Controller
{
    public function showQuote(Request $request): View
    {
        $dataStr = $request->query('data');
        if (!$dataStr) {
            abort(404, 'Quote reference link is missing or expired.');
        }

        $quote = json_decode(base64_decode($dataStr), true);
        if (!$quote || !is_array($quote)) {
            abort(404, 'Invalid or corrupted quotation data.');
        }

        return view('public.quote-view', [
            'quote' => $quote,
        ]);
    }
}
