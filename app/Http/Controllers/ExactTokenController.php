<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExactToken;

class ExactTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
            'refresh_token' => 'required|string',
            'token_type' => 'required|string',
            'api_response' => 'required|string',
        ]);

        ExactToken::updateOrCreate(
            [],
            [
                'access_token' => $request->access_token,
                'refresh_token' => $request->refresh_token,
                'token_type' => $request->token_type,
                'api_response' => $request->api_response,
            ]
        );

        return response()->json(['message' => 'Token stored successfully!'], 200);
    }
}
