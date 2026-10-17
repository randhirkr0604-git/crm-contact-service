<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Contact;
use App\Services\ContactCreationService;
use PHPUnit\Framework\MockObject\MockObject;
use App\Services\ContactSources\Contracts\ContactSourceInterface;

class ContactCreationServiceTest extends TestCase
{
    public function test_service_creates_contact_using_any_source(): void
    {
        $contact = new Contact([
            'name' => 'Test Contact',
            'email' => 'test123@gmail.com',
        ]);

        $source = $this->createMock(
            ContactSourceInterface::class
        );

        $source->expects($this->once())
            ->method('createContact')
            ->willReturn($contact);

        $service = new ContactCreationService();

        $result = $service->create($source);

        $this->assertSame($contact, $result);
    }

    public function test_service_accepts_a_new_source_without_modification(): void
    {
        // Simulate a future source, such as CustomerSource.
        $newSource = new class implements ContactSourceInterface {

            public function createContact(): Contact
            {
                return new Contact([
                    'name' => 'Future Customer',
                    'email' => 'dummy123@gmail.com',
                ]);
            }
        };

        $service = new ContactCreationService();

        $contact = $service->create($newSource);

        $this->assertInstanceOf(Contact::class, $contact);

        $this->assertEquals(
            'Future Customer',
            $contact->name
        );

        $this->assertEquals(
            'dummy123@gmail.com',
            $contact->email
        );
    }
}
