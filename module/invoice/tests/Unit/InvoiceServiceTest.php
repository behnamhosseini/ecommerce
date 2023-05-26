<?php

namespace INVOICE\tests\Unit;

use INVOICE\Repository\v1\InvoiceRepository;
use INVOICE\Service\v1\InvoiceService;
use PHPUnit\Framework\TestCase;
use PRODUCT\Repository\v1\ProductRepository;
use PRODUCT\Service\v1\ProductService;

class InvoiceServiceTest extends TestCase
{
    /**
     * @dataProvider amountDataProvider
     */
    public function testCalculateAmount(float $quantity, int $price, int $expectedResult)
    {
        $calculation = new InvoiceService(new InvoiceRepository(),new ProductService(new ProductRepository()));
        $result = $calculation->calculateAmount($quantity, $price);

        $this->assertEquals($expectedResult, $result);
    }

    public function amountDataProvider()
    {
        return [
            [2.5, 100, 250],
            [3.75, 80, 300],
            [1.5, 200, 300],
        ];
    }

    /**
     * @dataProvider discountDataProvider
     */
    public function testCalculateDiscount(float $amount, int $discount, int $expectedResult)
    {
        $calculation = new InvoiceService(new InvoiceRepository(),new ProductService(new ProductRepository()));
        $result = $calculation->calculateDiscount($amount, $discount);

        $this->assertEquals($expectedResult, $result);
    }

    public function discountDataProvider()
    {
        return [
            [500, 20, 100],
            [800, 10, 80],
            [1000, 5, 50],
        ];
    }

    /**
     * @dataProvider totalAfterDiscountDataProvider
     */
    public function testCalculateTotalAfterDiscount(int $amount, int $discount, int $expectedResult)
    {
        $calculation = new InvoiceService(new InvoiceRepository(),new ProductService(new ProductRepository()));
        $result = $calculation->calculateTotalAfterDiscount($amount, $discount);

        $this->assertEquals($expectedResult, $result);
    }

    public function totalAfterDiscountDataProvider()
    {
        return [
            [500, 100, 400],
            [800, 200, 600],
            [1000, 50, 950],
        ];
    }

    /**
     * @dataProvider taxDataProvider
     */
    public function testCalculateTax(int $totalAfterDiscount, int $tax, int $expectedResult)
    {
        $calculation = new InvoiceService(new InvoiceRepository(),new ProductService(new ProductRepository()));
        $result = $calculation->calculateTax($totalAfterDiscount, $tax);

        $this->assertEquals($expectedResult, $result);
    }

    public function taxDataProvider()
    {
        return [
            [400, 10, 40],
            [600, 20, 120],
            [1000, 5, 50],
        ];
    }

    /**
     * @dataProvider totalDueDataProvider
     */
    public function testCalculateTotalDue(int $totalAfterDiscount, int $tax, int $expectedResult)
    {
        $calculation = new InvoiceService(new InvoiceRepository(),new ProductService(new ProductRepository()));
        $result = $calculation->calculateTotalDue($totalAfterDiscount, $tax);

        $this->assertEquals($expectedResult, $result);
    }

    public function totalDueDataProvider()
    {
        return [
            [400, 40, 440],
            [600, 50, 650],
            [1000, 0, 1000],
        ];
    }

    /**
     * @dataProvider totalSumDataProvider
     */
    public function testCalculateTotalSum(array $amounts, int $expectedResult)
    {
        $calculation = new InvoiceService(new InvoiceRepository(),new ProductService(new ProductRepository()));
        $result = $calculation->calculateTotalSum($amounts);

        $this->assertEquals($expectedResult, $result);
    }

    public function totalSumDataProvider()
    {
        return [
            [[100, 200, 300], 600],
            [[500, 1000, 1500], 3000],
            [[50, 75, 100, 125], 350],
        ];
    }
}
