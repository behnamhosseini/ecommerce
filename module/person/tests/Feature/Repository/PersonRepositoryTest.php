<?php


namespace PERSON\tests\Feature\Repository;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PERSON\database\factories\PersonFactory;
use PERSON\database\seeders\PersonSeeder;
use PERSON\Models\Person;
use PERSON\Repository\v1\PersonRepository;
use Tests\TestCase;

class PersonRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $personRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->personRepository = new PersonRepository();
        $this->seed(PersonSeeder::class);
    }

    public function testCreatePerson()
    {
        // Existing person data for uniqueness test
        $existingPersonData = [
            'social_id' => '1234567890',
            'mobile_number' => '1234567890',
            'email' => 'john.doe@example.com',
        ];

        // Create an existing person
        PersonFactory::new()->create($existingPersonData);

        $personData = [
            'active' => true,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'social_id' => '9876543210',
            'birth_date' => '1990-01-01',
            'mobile_number' => '9876543210',
            'mobile_number_description' => 'Mobile number description',
            'email' => 'johndoe@example.com',
            'email_description' => 'Email description'
        ];

        $person = $this->personRepository->create($personData);

        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals($personData['active'], $person->active);
        $this->assertEquals($personData['first_name'], $person->first_name);
        $this->assertEquals($personData['last_name'], $person->last_name);
        $this->assertEquals($personData['social_id'], $person->social_id);
        $this->assertEquals($personData['birth_date'], $person->birth_date);
        $this->assertEquals($personData['mobile_number'], $person->mobile_number);
        $this->assertEquals($personData['mobile_number_description'], $person->mobile_number_description);
        $this->assertEquals($personData['email'], $person->email);
        $this->assertEquals($personData['email_description'], $person->email_description);
    }

    public function testCreatePersonWithDuplicateSocialId()
    {
        //ToDo:
    }

    public function testCreatePersonWithDuplicateMobileNumber()
    {
        //ToDo:
    }

    public function testCreatePersonWithDuplicateEmail()
    {
        //ToDo:
    }

    public function testGetPersonById()
    {
        $person = PersonFactory::new()->create();

        // Get the person by ID
        $foundPerson = $this->personRepository->findById($person->id);

        $this->assertInstanceOf(Person::class, $foundPerson);
        $this->assertEquals($person->id, $foundPerson->id);
        $this->assertEquals($person->active, $foundPerson->active);
        $this->assertEquals($person->first_name, $foundPerson->first_name);
        $this->assertEquals($person->last_name, $foundPerson->last_name);
        $this->assertEquals($person->social_id, $foundPerson->social_id);
        $this->assertEquals($person->birth_date, $foundPerson->birth_date);
        $this->assertEquals($person->mobile_number, $foundPerson->mobile_number);
        $this->assertEquals($person->mobile_number_description, $foundPerson->mobile_number_description);
        $this->assertEquals($person->email, $foundPerson->email);
        $this->assertEquals($person->email_description, $foundPerson->email_description);
        // Assert other fields
    }
    public function testUpdatePerson()
    {
        $person = PersonFactory::new()->create();

        $updatedPersonData = [
            'active' => false,
            'first_name' => 'Updated',
            'last_name' => 'Person',
            'birth_date' => '1995-01-01',
            'mobile_number' => '9876543210',
            'mobile_number_description' => 'Updated mobile number description',
            'email' => 'updatedperson@example.com',
            'email_description' => 'Updated email description'
        ];

        $updatedPerson = $this->personRepository->update($person->id, $updatedPersonData);

        $this->assertInstanceOf(Person::class, $updatedPerson);
        $this->assertEquals($person->id, $updatedPerson->id);
        $this->assertEquals($updatedPersonData['active'], $updatedPerson->active);
        $this->assertEquals($updatedPersonData['first_name'], $updatedPerson->first_name);
        $this->assertEquals($updatedPersonData['last_name'], $updatedPerson->last_name);
        $this->assertEquals($person->social_id, $updatedPerson->social_id); // Social ID should not be updated
        $this->assertEquals($updatedPersonData['birth_date'], $updatedPerson->birth_date);
        $this->assertEquals($updatedPersonData['mobile_number'], $updatedPerson->mobile_number);
        $this->assertEquals($updatedPersonData['mobile_number_description'], $updatedPerson->mobile_number_description);
        $this->assertEquals($updatedPersonData['email'], $updatedPerson->email);
        $this->assertEquals($updatedPersonData['email_description'], $updatedPerson->email_description);
        // Assert other fields
    }

    public function testDeletePerson()
    {
        $person = PersonFactory::new()->create();

        $this->personRepository->delete($person->id);

        $this->assertDatabaseMissing('people', ['id' => $person->id]);
    }
}
