# CRM Contact Creation Service

A Laravel-based CRM application that automatically creates a Contact whenever a new Account or Lead is created.

The application follows an extensible, service-oriented architecture that allows contacts to be created from multiple sources without modifying the core contact creation service.

## 1. Project Overview

The objective of this project is to build a reusable contact creation service that accepts different sources.

Currently supported sources:

- **Account** – Automatically creates a contact when an account is created.
- **Lead** – Automatically creates a contact when a lead is created.

The architecture is designed to support additional sources in the future, such as Customers, Orders, Partners, or other CRM entities.

### Key Features

- Centralized Contact Creation Service.
- Extensible source-based architecture.
- Source-specific services.
- Polymorphic relationship between contacts and their sources.
- Separate Account and Lead creation forms.
- jQuery AJAX form submissions.
- Frontend and backend validation.
- JSON responses for AJAX requests.
- Automated unit and feature testing.

## 2. Technology Stack

| Technology | Purpose |
|---|---|
| PHP | Backend development |
| Laravel 13 | Application framework |
| MySQL | Database |
| Blade | Frontend templates |
| jQuery AJAX | Asynchronous form submissions |
| Bootstrap | Responsive UI |
| PHPUnit / Laravel Testing | Automated testing |
| Git & GitHub | Version control |

## 3. Application Architecture

The application follows a service-oriented architecture with a common interface for all contact sources.

### Architecture Flow

```text
                 User
                  |
          +-------+-------+
          |               |
     Account Form      Lead Form
          |               |
     AJAX Request     AJAX Request
          |               |
   AccountController  LeadController
          |               |
    AccountSource      LeadSource
          |               |
          +-------+-------+
                  |
          ContactCreationService
                  |
             Contact Model
                  |
               MySQL
```

### Main Components

**1. ContactCreationService**

Location: `app/Services/ContactCreationService.php`

This is the central service responsible for contact creation.

It accepts any source implementing the common contact source interface.

The service does not need to know whether the source is an Account, Lead, or any future entity.

**2. ContactSourceInterface**

Location: `app/Services/ContactSources/Contracts/ContactSourceInterface.php`

Defines the contract that every contact source must implement.

This ensures all source services follow a consistent structure.

**3. Source Services**

Location: `app/Services/ContactSources/`

Currently implemented:

- `AccountSource.php`
- `LeadSource.php`

Each source service is responsible for providing the contact information associated with its respective entity.

**4. Controllers**

- `AccountController.php`
- `LeadController.php`

Controllers handle incoming requests, validate input, create the corresponding entity, and invoke the contact creation workflow.

**5. Models**

- Account
- Lead
- Contact

The Contact model uses a polymorphic relationship to associate a contact with different source models.

This avoids creating separate contact relationship tables for every source type.

## 4. Extensibility: Adding a New Source

One of the primary requirements is the ability to introduce new contact sources without modifying the existing ContactCreationService.

For example, suppose we want to create contacts from a Customer entity in the future.

### Step 1: Create CustomerSource

Create:

`app/Services/ContactSources/CustomerSource.php`

Implement the existing ContactSourceInterface.

```php
<?php

namespace App\Services\ContactSources;

use App\Models\Contact;
use App\Services\ContactSources\Contracts\ContactSourceInterface;

class CustomerSource implements ContactSourceInterface
{
    public function createContact(): Contact
    {
        // Map customer information to a Contact.

        return new Contact([
            'name' => 'Customer Name',
            'email' => 'customer@example.com',
        ]);
    }
}
```

The example above illustrates the source contract. In the actual application, customer data should come from the Customer model.

### Step 2: Use the Existing Service

```php
$customerSource = new CustomerSource();

$contact = app(ContactCreationService::class)
    ->create($customerSource);
```

No changes are required in the existing ContactCreationService.

This follows the Open/Closed Principle: the existing service is open for extension but closed for modification.

## 5. Installation and Setup

Follow these steps to run the application locally.

### Prerequisites

Ensure the following are installed:

- PHP 8.3 or later
- Composer
- MySQL 8 or compatible version
- Node.js and npm
- Git

### Step 1: Clone the Repository

```bash
git clone https://github.com/randhirkr0604-git/crm-contact-service.git

cd crm-contact-service
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Configure Environment

Copy the example environment file.

For Windows:

```bash
copy .env.example .env
```

For Linux/macOS:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### Step 4: Configure MySQL

Create a database in MySQL:

```sql
CREATE DATABASE crm_contact_service;
```

Update the database configuration in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_contact_service
DB_USERNAME=root
DB_PASSWORD=
```

Update the username and password according to your local MySQL configuration.

### Step 5: Run Database Migrations

```bash
php artisan migrate
```

This creates the required application tables.

### Step 6: Install Frontend Dependencies

If frontend dependencies are required:

```bash
npm install
npm run build
```

### Step 7: Start the Application

```bash
php artisan serve
```

Open:

`http://127.0.0.1:8000`

## 6. Application Usage

The application provides two separate forms.

### Create Account

URL:

`http://127.0.0.1:8000/accounts/create`

The form accepts:

- Company Name
- Contact Name
- Email Address
- Phone Number

On successful submission:

1. An Account record is created.
2. The Account source service is invoked.
3. The ContactCreationService creates the associated contact.
4. A JSON response is returned to the frontend.

### Create Lead

URL:

`http://127.0.0.1:8000/leads/create`

The form accepts:

- Lead Name
- Contact Name
- Email Address
- Phone Number

On successful submission:

1. A Lead record is created.
2. The Lead source service is invoked.
3. The ContactCreationService creates the associated contact.
4. A JSON response is returned to the frontend.

## 7. AJAX Submission and Validation

Both forms use jQuery AJAX for asynchronous submissions.

Frontend validation provides immediate feedback to users.

Backend validation is also implemented using Laravel validation rules to ensure that invalid data cannot bypass validation.

The application returns appropriate JSON responses for successful submissions and validation errors.

## 8. Automated Testing

The application uses Laravel's testing framework.

Run all automated tests:

```bash
php artisan test
```

Or:

```bash
vendor/bin/phpunit
```

The tests should cover:

- Account creation and associated contact creation.
- Lead creation and associated contact creation.
- Invalid request validation.
- Correct source association.
- ContactCreationService behavior.
- Adding a new source without modifying the core service.

**Testing recommendation:** Configure a separate testing database, such as SQLite in-memory, to prevent automated tests from modifying development data.

## 9. Design Decisions and Assumptions

The following assumptions were made where the assignment did not specify implementation details.

1. **Contact Information:** Each source provides the contact name, email address, and optional phone number.

2. **Separate Forms:** Account and Lead have separate creation forms because they represent different CRM entities.

3. **Contact Creation:** A contact is automatically created after its corresponding Account or Lead is created.

4. **Source Abstraction:** Every source must implement the common ContactSourceInterface.

5. **Source Services:** Sources are implemented as separate services so that source-specific mapping logic remains independent of the central contact creation service.

6. **Polymorphic Relationship:** A contact can belong to different source models through a polymorphic relationship.

7. **Validation:** Both frontend and backend validation are implemented. Backend validation is considered authoritative.

8. **AJAX:** Form submissions use jQuery AJAX instead of traditional page reloads.

9. **Extensibility:** New source services can be introduced by implementing the existing interface without changing the core ContactCreationService.

10. **Duplicate Contacts:** Each successful Account or Lead creation creates its own associated contact. Contact deduplication based on email address is not part of the current scope.

11. **Authentication:** Authentication and authorization are outside the scope of this assignment.

## 10. Future Improvements

Potential improvements include:

- Additional contact sources such as Customers and Partners.
- Contact deduplication and merging.
- Contact update and deletion workflows.
- API endpoints for external integrations.
- Event-driven contact creation.
- Advanced contact search and filtering.
- Authentication and role-based permissions.

## 11. Repository

GitHub:

https://github.com/randhirkr0604-git/crm-contact-service

---

**Developed as a Laravel CRM Contact Creation Service assignment.**