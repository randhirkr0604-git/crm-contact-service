<?php
namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use App\Services\ContactSources\Contracts\ContactSourceInterface;

class ContactCreationService
{
    public function create(ContactSourceInterface $source): Contact 
    {
        
            return $source->createContact();
       
    }
    
}