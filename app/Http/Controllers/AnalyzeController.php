<?php

namespace App\Http\Controllers;

use App\Ai\Agents\SalesCoach;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyzeController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'transcript' => ['required', 'string'],
        ]);

        $response = (new SalesCoach)->prompt($validated['transcript']);

        return response()->json(['analysis' => $response->text]);
    }
}