<?php


namespace INVOICE\tests\Feature\Service;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use INVOICE\database\factories\InvoiceFactory;
use INVOICE\Events\InvoiceActionEvent;
use INVOICE\Models\Invoice;
use INVOICE\Repository\v1\InvoiceRepository;
use INVOICE\Repository\v1\InvoiceRepositoryInterface;
use INVOICE\Service\v1\InvoiceService;
use PERSON\database\factories\PersonFactory;
use PERSON\Service\v1\PersonService;
use PERSON\Service\v1\PersonServiceInterface;
use PRODUCT\database\factories\ProductFactory;
use PRODUCT\Service\v1\ProductService;
use PRODUCT\Service\v1\ProductServiceInterface;
use Tests\TestCase;

class InvoiceServiceTest extends TestCase
{
    protected $invoiceRepositoryMock;
    protected $personServiceMock;
    protected $productServiceMock;
    protected $invoiceService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create mock objects for the dependencies
        $this->invoiceRepositoryMock = $this->createMock(InvoiceRepository::class);
        $this->personServiceMock = $this->createMock(PersonService::class);
        $this->productServiceMock = $this->createMock(ProductService::class);

        // Create an instance of the InvoiceService
        $this->invoiceService = new InvoiceService(
            $this->invoiceRepositoryMock,
            $this->productServiceMock,
            $this->personServiceMock
        );
    }

    public function testGetAllInvoices()
    {
        // Create a mock invoice collection
        $invoices = collect([Invoice::make(['id' => 1]), Invoice::make(['id' => 2])]);

        // Set up expectation for the mock repository method
        $this->invoiceRepositoryMock->expects($this->once())
            ->method('getAll')
            ->willReturn($invoices);

        // Invoke the getAllInvoices method
        $result = $this->invoiceService->getAllInvoices();

        // Assert that the result is the same as the mock invoice collection
        $this->assertSame($invoices, $result);
    }

    public function testGetInvoice()
    {
        $id = 1;
        $invoice = Invoice::make(['id' => $id]);

        // Set up expectation for the mock repository method
        $this->invoiceRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($invoice);

        // Invoke the getInvoice method
        $result = $this->invoiceService->getInvoice($id);

        // Assert that the result is the same as the mock invoice
        $this->assertSame($invoice, $result);
    }

    public function testCreateInvoice()
    {
        // Arrange
        $data = [
            'person_id' => 1,
            'items' => [
                1 => 2,
                2 => 3,
            ],
        ];
        $person = app()->make(PersonFactory::class)->make(['active' => true]);
        $product1 = app()->make(ProductFactory::class)->make(['inventory' => 5, 'selling_price' => 100, 'discount_percentage' => 10, 'tax' => 5]);
        $product2 = app()->make(ProductFactory::class)->make(['inventory' => 10, 'selling_price' => 200, 'discount_percentage' => 15, 'tax' => 10]);
        $invoice = app()->make(InvoiceFactory::class)->make(['id' => 1, 'total_sum' => 1100]);

        $personServiceMock = $this->createMock(PersonServiceInterface::class);
        $personServiceMock->expects($this->exactly(2))
            ->method('getPersonById')
            ->with(1)
            ->willReturn((object)['active' => true]);


        $this->personServiceMock->expects($this->once())->method('getPersonById')->with($data['person_id'])->willReturn($person);
        $this->productServiceMock->expects($this->exactly(2))->method('getProductById')->willReturn($product1, $product2);
        $this->invoiceRepositoryMock->expects($this->once())->method('create')->with($data)->willReturn($invoice);
        $this->invoiceRepositoryMock->expects($this->exactly(2))->method('attachItems');
        $this->invoiceRepositoryMock->expects($this->once())->method('update')->with($invoice->id, ['total_sum' => 1100]);
        $this->invoiceRepositoryMock->expects($this->once())->method('findById')->with($invoice->id)->willReturn($invoice);
        $this->productServiceMock->expects($this->exactly(2))->method('getProductById')->willReturn($product1, $product2);
        $this->invoiceRepositoryMock->expects($this->once())->method('findById')->with($invoice->id)->willReturn($invoice);
        $this->productServiceMock->expects($this->exactly(2))->method('getProductById')->willReturn($product1, $product2);
        Event::fake();

        // Act
        $result = $this->invoiceService->createInvoice($data);
        // Assert
        $this->assertEquals($invoice, $result);
        Event::assertDispatched(InvoiceActionEvent::class, function ($event) use ($product1, $product2) {
            return $event->getInventory() === ($product1->inventory - 2) &&
                $event->getProductId() === $product1->id;
        });
        Event::assertDispatched(InvoiceActionEvent::class, function ($event) use ($product1, $product2) {
            return $event->getInventory() === ($product2->inventory - 3) &&
                $event->getProductId() === $product2->id;
        });
    }

       public function testUpdateInvoice()
       {
           // Arrange
           $id = 1;
           $data = [
               'person_id' => 1,
               'items' => [
                   1 => 2,
                   2 => 3,
               ],
           ];
           $person = app()->make(PersonFactory::class)->make(['active' => true]);
           $product1 = app()->make(ProductFactory::class)->make(['inventory' => 5, 'selling_price' => 100, 'discount_percentage' => 10, 'tax' => 5]);
           $product2 = app()->make(ProductFactory::class)->make(['inventory' => 10, 'selling_price' => 200, 'discount_percentage' => 15, 'tax' => 10]);
           $invoice = app()->make(InvoiceFactory::class)->make(['id' => 1, 'total_sum' => 1100]);

           $this->personServiceMock->expects($this->once())->method('getPersonById')->with($data['person_id'])->willReturn($person);
           $this->invoiceRepositoryMock->expects($this->once())->method('findById')->with($id)->willReturn($invoice);
           $this->productServiceMock->expects($this->exactly(2))->method('getProductById')->willReturn($product1, $product2);
           $this->invoiceRepositoryMock->expects($this->exactly(2))->method('attachItems');
           $this->invoiceRepositoryMock->expects($this->once())->method('update')->with($invoice->id, ['total_sum' => 1100]);
           $this->invoiceRepositoryMock->expects($this->once())->method('findById')->with($invoice->id)->willReturn($invoice);
           $this->productServiceMock->expects($this->exactly(2))->method('getProductById')->willReturn($product1, $product2);
           Event::fake();

           // Act
           $result = $this->invoiceService->updateInvoice($id, $data);

           // Assert
           $this->assertEquals($invoice, $result);
           Event::assertDispatched(InvoiceActionEvent::class, function ($event) use ($product1, $product2) {
               return $event->getInventory() === ($product1->inventory - 2) &&
                   $event->getProductId() === $product1->id;
           });
           Event::assertDispatched(InvoiceActionEvent::class, function ($event) use ($product1, $product2) {
               return $event->getInventory() === ($product2->inventory - 3) &&
                   $event->getProductId() === $product2->id;
           });
       }

    public function testDeleteInvoice()
    {
        // Prepare test data
        $id = 1;

        // Create a mock invoice object
        $invoice = Invoice::make(['id' => $id]);

        // Set up expectations for the mock objects
        $this->invoiceRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($invoice);

        $this->invoiceRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(true);

        // Invoke the deleteInvoice method
        $result = $this->invoiceService->deleteInvoice($id);

        // Assert that the result is true
        $this->assertTrue($result);
    }

    public function testCalculateAmount()
    {
        $quantity = 5;
        $price = 100;

        $result = $this->invoiceService->calculateAmount($quantity, $price);

        $this->assertEquals(500, $result);
    }

    public function testCalculateDiscount()
    {
        $amount = 500;
        $discount = 10;

        $result = $this->invoiceService->calculateDiscount($amount, $discount);

        $this->assertEquals(50, $result);
    }

    public function testCalculateTotalAfterDiscount()
    {
        $amount = 500;
        $discount = 50;

        $result = $this->invoiceService->calculateTotalAfterDiscount($amount, $discount);

        $this->assertEquals(450, $result);
    }

    public function testCalculateTax()
    {
        $totalAfterDiscount = 450;
        $tax = 5;

        $result = $this->invoiceService->calculateTax($totalAfterDiscount, $tax);

        $this->assertEquals(22, $result);
    }

    public function testCalculateTotalDue()
    {
        $totalAfterDiscount = 450;
        $tax = 22;

        $result = $this->invoiceService->calculateTotalDue($totalAfterDiscount, $tax);

        $this->assertEquals(472, $result);
    }

}
