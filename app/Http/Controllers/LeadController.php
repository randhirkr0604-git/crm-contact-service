<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use App\Services\ContactCreationService;
use App\Services\ContactSources\LeadSource;

class LeadController extends Controller
{
    public function create()
    {
        return view('leads.create');
    }

    public function store(Request $request, ContactCreationService $contactCreationService){
        
        $validated = $request->validate([
            'lead_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => [
                    'nullable',
                    'string',
                    'max:20',
                    'regex:/^[0-9+\-\s()]{7,20}$/',
                ],
        ]);

        $lead = DB::transaction(function () use(
            $validated,
            $contactCreationService
        ){
            $lead = Lead::create($validated);

            $contactCreationService->create(
                new LeadSource($lead)
            );

            return $lead;
        });

        return response()->json([
            'success' => true,
            'message' => 'Lead saved successfully',
            'data' => $lead
        ], 201);

    }
}
