<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use App\Services\ContactCreationService;
use App\Services\ContactSources\AccountSource;

class AccountController extends Controller
{
    public function create(){

        return view('accounts.create');

    }

    public function store(
        Request $request,
        ContactCreationService $contactCreationService
    ){

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => [
                    'nullable',
                    'string',
                    'max:20',
                    'regex:/^[0-9+\-\s()]{7,20}$/',
                ],
        ]);

        $account = DB::transaction(function () use(
            $validated,
            $contactCreationService,
        ){
            $account = Account::create($validated);

            $contactCreationService->create(
                new AccountSource($account)
            );

            return $account;

        });

        return response()->json([
            'success' => true,
            'message' => 'Account and contact created successfully.',
            'data' => $account,
        ], 201);

    }
}
