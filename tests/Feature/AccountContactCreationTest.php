<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Account;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountContactCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_creation_also_creates_contact(): void
    {
        $response = $this->postJson(route('accounts.store'), [
            'company_name' => 'ABC Technologies',
            'contact_name' => 'Randhir Kr',
            'email' => 'randhir.kr0604@gmail.com',
            'phone' => '9876543210',
        ]);

        $response->assertCreated()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('accounts', [
            'company_name' => 'ABC Technologies',
            'email' => 'randhir.kr0604@gmail.com',
        ]);

        $this->assertDatabaseHas('contacts', [
            'name' => 'Randhir Kr',
            'email' => 'randhir.kr0604@gmail.com',
            'source_type' => Account::class,
        ]);

        $this->assertDatabaseCount('accounts', 1);
        $this->assertDatabaseCount('contacts', 1);
    }

    public function test_account_creation_fails_with_invalid_data(): void
    {
        $response = $this->postJson(route('accounts.store'), [
            'company_name' => '',
            'contact_name' => '',
            'email' => 'invalid-email',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'company_name',
                'contact_name',
                'email',
            ]);

        $this->assertDatabaseCount('accounts', 0);
        $this->assertDatabaseCount('contacts', 0);
    }
}
