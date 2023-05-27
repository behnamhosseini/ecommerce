<?php


namespace INVOICE\tests\Feature\Repository;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use INVOICE\database\factories\InvoiceFactory;
use INVOICE\database\factories\InvoiceItemFactory;
use INVOICE\Repository\v1\InvoiceRepository;
use INVOICE\Service\v1\InvoiceService;
use PERSON\Repository\v1\PersonRepository;
use PERSON\Service\v1\PersonService;
use PRODUCT\database\factories\ProductFactory;
use PRODUCT\Repository\v1\ProductRepository;
use PRODUCT\Service\v1\ProductService;
use Tests\TestCase;

class InvoiceRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected $invoiceRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->invoiceRepository = new InvoiceRepository();
    }

    public function testGetAll()
    {
        $invoice1 = app()->make(InvoiceFactory::class)->has(app()->make(InvoiceItemFactory::class)->count(3))->create();
        $invoice2 = app()->make(InvoiceFactory::class)->has(app()->make(InvoiceItemFactory::class)->count(2))->create();
        $invoices = $this->invoiceRepository->getAll();

        $this->assertCount(2, $invoices);
        $this->assertEquals($invoice1->id, $invoices[0]->id);
        $this->assertEquals($invoice2->id, $invoices[1]->id);
        $this->assertCount(3, $invoices[0]->invoiceItems);
        $this->assertCount(2, $invoices[1]->invoiceItems);
    }

    public function testFindById()
    {
        $invoice = app()->make(InvoiceFactory::class)->has(app()->make(InvoiceItemFactory::class)->count(3))->create();
        $foundInvoice = $this->invoiceRepository->findById($invoice->id);
        $this->assertEquals($invoice->id, $foundInvoice->id);
        $this->assertCount(3, $foundInvoice->invoiceItems);
    }

    public function testCreate()
    {
        $data = [
            'person_id' => app()->make(InvoiceFactory::class)->create()->id,
            'total_sum' => 100,
        ];
        $createdInvoice = $this->invoiceRepository->create($data);
        $this->assertNotNull($createdInvoice);
        $this->assertEquals($data['person_id'], $createdInvoice->person_id);
        $this->assertEquals($data['total_sum'], $createdInvoice->total_sum);
    }

    public function testUpdate()
    {
        $invoice = app()->make(InvoiceFactory::class)->create();
        $data = [
            'total_sum' => 200,
        ];
        $result = $this->invoiceRepository->update($invoice->id, $data);
        $this->assertTrue($result);
        $this->assertEquals($data['total_sum'], $invoice->fresh()->total_sum);
    }

    public function testDelete()
    {
        $invoice = app()->make(InvoiceFactory::class)->has(app()->make(InvoiceItemFactory::class)->count(3))->create();
        $result = $this->invoiceRepository->delete($invoice->id);

        $this->assertTrue($result);
//        $this->assertDeleted($invoice);
//        $this->assertDeleted($invoice->invoceItems);
    }

    public function testAttachItems()
    {
        $calculation = new InvoiceService(new InvoiceRepository(),new ProductService(new ProductRepository()),new PersonService(new PersonRepository()));

        // Create a mock invoice
        $invoice = app()->make(InvoiceFactory::class)->create();
        $product = app()->make(ProductFactory::class)->create();
        $invoiceItems = [
            'product_id' => $product->id,
            'quantity' => $quantity = 5,
            'price' => $product->selling_price,
            'amount' => $amount = $calculation->calculateAmount($quantity, $product->selling_price),
            'discount' => $discount = $calculation->calculateDiscount($amount, $product->discount_percentage),
            'total_after_discount' => $total_after_discount = $calculation->calculateTotalAfterDiscount($amount, $discount),
            'tax' => $tax = $calculation->calculateTax($total_after_discount, $product->tax),
            'total_due' => $calculation->calculateTotalDue($total_after_discount, $tax)
        ];
        // Attach items to the invoice using the repository method
        $this->invoiceRepository->attachItems($invoice, $invoiceItems);

        // Retrieve the invoice items associated with the invoice
        $invoiceItems = $invoice->invoiceItems;

        // Assert that the number of attached items matches the created items
        $this->assertEquals(1, $invoiceItems->count());
    }
}
