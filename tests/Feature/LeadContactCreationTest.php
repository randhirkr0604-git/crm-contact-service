<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeadContactCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_creation_also_creates_contact(): void
    {
        $response = $this->postJson(route('leads.store'), [
            'lead_name' => 'XYZ Solutions',
            'contact_name' => 'Rahul Kumar',
            'email' => 'rahul876@gmail.com',
            'phone' => '9988776655',
        ]);

        $response->assertCreated()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('leads', [
            'lead_name' => 'XYZ Solutions',
            'email' => 'rahul876@gmail.com',
        ]);

        $this->assertDatabaseHas('contacts', [
            'name' => 'Rahul Kumar',
            'email' => 'rahul876@gmail.com',
            'source_type' => Lead::class,
        ]);

        $this->assertDatabaseCount('leads', 1);
        $this->assertDatabaseCount('contacts', 1);
    }

    public function test_lead_creation_fails_with_invalid_data(): void
    {
        $response = $this->postJson(route('leads.store'), [
            'lead_name' => '',
            'contact_name' => '',
            'email' => 'invalid-email',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'lead_name',
                'contact_name',
                'email',
            ]);

        $this->assertDatabaseCount('leads', 0);
        $this->assertDatabaseCount('contacts', 0);
    }
}
