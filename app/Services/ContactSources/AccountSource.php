<?php
namespace App\Services\ContactSources;

use App\Models\Account;
use App\Models\Contact;
use App\Services\ContactSources\Contracts\ContactSourceInterface;

class AccountSource implements ContactSourceInterface
{
    public function __construct(
        private Account $account
    ) {}

    public function createContact(): Contact
    {
        return $this->account->contacts()->create([
            'name' => $this->account->contact_name,
            'email' =>$this->account->email,
            'phone' =>$this->account->phone,
        ]);
    }
    
}