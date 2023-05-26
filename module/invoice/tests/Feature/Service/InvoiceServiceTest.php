<?php


namespace INVOICE\tests\Feature\Service;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use INVOICE\Repository\v1\InvoiceRepositoryInterface;
use INVOICE\Service\v1\InvoiceService;
use PERSON\Service\v1\PersonServiceInterface;
use PRODUCT\Service\v1\ProductServiceInterface;
use Tests\TestCase;

class InvoiceServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetAllInvoices()
    {
        // Arrange
        $invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $invoiceRepositoryMock->expects($this->once())
            ->method('getAll')
            ->willReturn(['invoice1', 'invoice2']);

        $productServiceMock = $this->createMock(ProductServiceInterface::class);
        $personServiceMock = $this->createMock(PersonServiceInterface::class);

        $invoiceService = new InvoiceService($invoiceRepositoryMock, $productServiceMock, $personServiceMock);

        // Act
        $result = $invoiceService->getAllInvoices();

        // Assert
        $this->assertEquals(['invoice1', 'invoice2'], $result);
    }

    public function testCreateInvoiceWithDisabledUser()
    {
        // Arrange
        $invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $productServiceMock = $this->createMock(ProductServiceInterface::class);

        $personServiceMock = $this->createMock(PersonServiceInterface::class);
        $personServiceMock->expects($this->once())
            ->method('getPersonById')
            ->willReturn((object)['active' => false]);

        $invoiceService = new InvoiceService($invoiceRepositoryMock, $productServiceMock, $personServiceMock);

        // Act
        $result = $invoiceService->createInvoice(['person_id' => 1]);

        // Assert
        $this->assertEquals('User is disabled', $result);
    }

    public function testCreateInvoiceWithBuyableProducts()
    {
        // Arrange
        $invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $invoiceRepositoryMock->expects($this->once())
            ->method('create')
            ->willReturn((object)['id' => 1]);

        $productServiceMock = $this->createMock(ProductServiceInterface::class);
        $productServiceMock->expects($this->exactly(2))
            ->method('getProductById')
            ->willReturnOnConsecutiveCalls(
                (object)['id' => 1, 'inventory' => 10, 'selling_price' => 100, 'discount_percentage' => 10, 'tax' => 5],
                (object)['id' => 2, 'inventory' => 5, 'selling_price' => 200, 'discount_percentage' => 0, 'tax' => 10]
            );

        $personServiceMock = $this->createMock(PersonServiceInterface::class);
        $personServiceMock->expects($this->once())
            ->method('getPersonById')
            ->willReturn((object)['active' => true]);

        $invoiceService = new InvoiceService($invoiceRepositoryMock, $productServiceMock, $personServiceMock);

        // Act
        $result = $invoiceService->createInvoice([
            'person_id' => 1,
            'items' => [
                '1' => 5,
                '2' => 2,
            ],
        ]);
        // Assert
        $this->assertEquals((object)['id' => 1], $result);
    }


    public function testCreateInvoiceWithUnbuyableProducts()
    {
        // Arrange
        $invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $productServiceMock = $this->createMock(ProductServiceInterface::class);
        $personServiceMock = $this->createMock(PersonServiceInterface::class);

        $personServiceMock->expects($this->once())
            ->method('getPersonById')
            ->willReturn((object)['active' => true]);

        $invoiceService = new InvoiceService($invoiceRepositoryMock, $productServiceMock, $personServiceMock);

        // Act
        $result = $invoiceService->createInvoice([
            'person_id' => 1,
            'items' => [
                '1' => 15, // Quantity exceeds inventory
            ],
        ]);

        // Assert
        $this->assertEquals(['1'], $result);
    }

    public function testUpdateInvoice()
    {
        // Arrange
        $invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $invoiceRepositoryMock->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn((object)['id' => 1]);

        $productServiceMock = $this->createMock(ProductServiceInterface::class);
        $productServiceMock->expects($this->once())
            ->method('getProductById')
            ->willReturn((object)['id' => 1, 'inventory' => 10, 'selling_price' => 100, 'discount_percentage' => 10, 'tax' => 5]);

        $personServiceMock = $this->createMock(PersonServiceInterface::class);
        $personServiceMock->expects($this->once())
            ->method('getPersonById')
            ->willReturn((object)['active' => true]);

        $invoiceService = new InvoiceService($invoiceRepositoryMock, $productServiceMock, $personServiceMock);

        // Act
        $result = $invoiceService->updateInvoice(1, [
            'person_id' => 1,
            'items' => [
                '1' => 5,
            ],
        ]);

        // Assert
        $this->assertEquals((object)['id' => 1], $result);
    }

    public function testDeleteInvoice()
    {
        // Arrange
        $invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $invoiceRepositoryMock->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn((object)['id' => 1]);

        $productServiceMock = $this->createMock(ProductServiceInterface::class);
        $personServiceMock = $this->createMock(PersonServiceInterface::class);

        $invoiceService = new InvoiceService($invoiceRepositoryMock, $productServiceMock, $personServiceMock);

        // Act
        $result = $invoiceService->deleteInvoice(1);

        // Assert
        $this->assertTrue($result);
    }

    public function testCalculateAmount()
    {
        // Arrange
        $invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $productServiceMock = $this->createMock(ProductServiceInterface::class);
        $personServiceMock = $this->createMock(PersonServiceInterface::class);

        $invoiceService = new InvoiceService($invoiceRepositoryMock, $productServiceMock, $personServiceMock);

        // Act
        $result = $invoiceService->calculateAmount(5, 100);

        // Assert
        $this->assertEquals(500, $result);
    }

    public function testCalculateDiscount()
    {
        // Arrange
        $invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $productServiceMock = $this->createMock(ProductServiceInterface::class);
        $personServiceMock = $this->createMock(PersonServiceInterface::class);

        $invoiceService = new InvoiceService($invoiceRepositoryMock, $productServiceMock, $personServiceMock);

        // Act
        $result = $invoiceService->calculateDiscount(500, 10);

        // Assert
        $this->assertEquals(50, $result);
    }

    public function testCalculateTotalAfterDiscount()
    {
        // Arrange
        $invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $productServiceMock = $this->createMock(ProductServiceInterface::class);
        $personServiceMock = $this->createMock(PersonServiceInterface::class);

        $invoiceService = new InvoiceService($invoiceRepositoryMock, $productServiceMock, $personServiceMock);

        // Act
        $result = $invoiceService->calculateTotalAfterDiscount(500, 10);
        // Assert
        $this->assertEquals(490, $result);
    }

    public function testCalculateTax()
    {
        // Arrange
        $invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $productServiceMock = $this->createMock(ProductServiceInterface::class);
        $personServiceMock = $this->createMock(PersonServiceInterface::class);

        $invoiceService = new InvoiceService($invoiceRepositoryMock, $productServiceMock, $personServiceMock);

        // Act
        $result = $invoiceService->calculateTax(450, 5);

        // Assert
        $this->assertEquals(22, $result);
    }

    public function testCalculateTotalDue()
    {
        // Arrange
        $invoiceRepositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $productServiceMock = $this->createMock(ProductServiceInterface::class);
        $personServiceMock = $this->createMock(PersonServiceInterface::class);

        $invoiceService = new InvoiceService($invoiceRepositoryMock, $productServiceMock, $personServiceMock);

        // Act
        $result = $invoiceService->calculateTotalDue(450, 22);

        // Assert
        $this->assertEquals(472, $result);
    }

}
