<?php


namespace INVOICE\tests\Feature\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use INVOICE\database\factories\InvoiceFactory;
use INVOICE\database\seeders\InvoiceSeeder;
use INVOICE\Models\Invoice;
use INVOICE\Repository\v1\InvoiceRepository;
use PERSON\database\factories\PersonFactory;
use PERSON\Models\Person;
use Tests\TestCase;

class InvoiceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $invoiceRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(InvoiceSeeder::class);
        $this->invoiceRepository = new InvoiceRepository();
    }

    public function testGetAllInvoices()
    {
        app()->make(InvoiceFactory::class)->count(10)->create();
        $invoices = $this->invoiceRepository->getAll();
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $invoices);
    }

    public function testGetInvoiceById()
    {
        $person = app()->make(PersonFactory::class)->create();
        $invoice=app()->make(InvoiceFactory::class)->create(['person_id' => $person->id,]);

        $invoiceId = $invoice->id;
        $retrievedInvoice = $this->invoiceRepository->findById($invoiceId);
        $this->assertInstanceOf(Invoice::class, $retrievedInvoice);
        $this->assertEquals($invoiceId, $retrievedInvoice->id);
    }

    public function testCreateInvoice()
    {
        $person = app()->make(PersonFactory::class)->create();

        $data = [
            'person_id' => $person->id,
        ];

        $createdInvoice = $this->invoiceRepository->create($data);

        $this->assertInstanceOf(Invoice::class, $createdInvoice);
    }

    public function testUpdateInvoice()
    {
        $person = app()->make(PersonFactory::class)->create();
        $invoice=app()->make(InvoiceFactory::class)->create(['person_id' => $person->id,]);
        $invoiceId = $invoice->id;
        $updatedData = [
            'person_id' => $person->id,
        ];

        $result = $this->invoiceRepository->update($invoiceId, $updatedData);

        $this->assertTrue($result);
    }

    public function testDeleteInvoice()
    {
        $person = app()->make(PersonFactory::class)->create();
        $invoice=app()->make(InvoiceFactory::class)->create(['person_id' => $person->id,]);
        $invoiceId = $invoice->id;
        $result = $this->invoiceRepository->delete($invoiceId);
        $this->assertTrue($result);
    }
}
