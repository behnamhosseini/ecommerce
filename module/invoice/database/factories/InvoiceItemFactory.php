<?php


namespace INVOICE\database\factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use INVOICE\Models\Invoice;
use INVOICE\Models\InvoiceItem;
use INVOICE\Repository\v1\InvoiceRepository;
use INVOICE\Service\v1\InvoiceService;
use PERSON\Repository\v1\PersonRepository;
use PERSON\Service\v1\PersonService;
use PRODUCT\database\factories\ProductFactory;
use PRODUCT\Models\Product;
use PRODUCT\Repository\v1\ProductRepository;
use PRODUCT\Service\v1\ProductService;

class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition()
    {
        $calculation = new InvoiceService(new InvoiceRepository(),new ProductService(new ProductRepository()),new PersonService(new PersonRepository()));
        $product = app()->make(ProductFactory::class)->create();
        return [
            'invoice_id' => function () {
                return  app()->make(InvoiceFactory::class)->create()->id;
            },
            'product_id' => $product->id,
            'quantity' => $quantity=$this->faker->randomFloat(2, 0, 999999.99),
            'price' => $product->selling_price,
            'amount' => $amount=$calculation->calculateAmount($quantity,$product->selling_price),
            'discount' => $discount=$calculation->calculateDiscount($amount,$product->discount_percentage),
            'total_after_discount' => $total_after_discount=$calculation->calculateTotalAfterDiscount($amount,$discount),
            'tax' => $tax=$calculation->calculateTax($total_after_discount,$product->tax),
            'total_due' => $calculation->calculateTotalDue($total_after_discount,$tax),
        ];
    }
}
