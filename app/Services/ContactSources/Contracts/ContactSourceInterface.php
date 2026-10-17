<?php
namespace App\Services\ContactSources\Contracts;

use App\Models\Contact;

interface ContactSourceInterface
{
    public function createContact(): Contact;
    
}