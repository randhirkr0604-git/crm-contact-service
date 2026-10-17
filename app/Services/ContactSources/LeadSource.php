<?php 
namespace App\Services\ContactSources;

use App\Models\Lead;
use App\Models\Contact;

use App\Services\ContactSources\Contracts\ContactSourceInterface;

class LeadSource implements ContactSourceInterface
{
    public function __construct(
        private Lead $lead
    ){}

    public function createContact(): Contact 
    {
        return $this->lead->contacts()->create([
            'name' => $this->lead->contact_name,
            'email'=> $this->lead->email,
            'phone'=> $this->lead->phone,
        ]);
    }
    
}