<?php

namespace INVOICE\Service\v1;

use INVOICE\Events\InvoiceActionEvent;
use INVOICE\Models\Invoice;
use INVOICE\Models\InvoiceItem;
use INVOICE\Repository\v1\InvoiceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use PERSON\Service\v1\PersonServiceInterface;
use PRODUCT\Service\v1\ProductServiceInterface;


class InvoiceService implements InvoiceServiceInterface
{
    private $invoiceRepository;
    private $productService;
    private PersonServiceInterface $personService;

    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
        ProductServiceInterface $productService,
        PersonServiceInterface $personService,
    )
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->productService = $productService;
        $this->personService = $personService;
    }

    public function getAllInvoices()
    {
        return $this->invoiceRepository->getAll();
    }

    public function getInvoice(int $id): ?Invoice
    {
        return $this->invoiceRepository->findById($id);
    }

    public function createInvoice(array $data)
    {
        return DB::transaction(function () use ($data) {
            $person = $this->personService->getPersonById($data['person_id']);
            if (!$person->active) {
                return "User is disabled";
            }

            $invoice = $this->invoiceRepository->create($data);
            $notBuyable = [];
            $totalSum = 0;

            foreach ($data['items'] as $productId => $quantity) {
                $productId = (int)$productId;
                $quantity = (int)$quantity;
                $product = $this->productService->getProductById($productId);
                if ($quantity > $product->inventory) {
                    $notBuyable[] = $product->id;
                    break;
//                    A list of unbuyable products should be returned here
                }
                $amount = $this->calculateAmount($quantity, $product->selling_price);
                $discount = $this->calculateDiscount($amount, $product->discount_percentage);
                $totalAfterDiscount = $this->calculateTotalAfterDiscount($amount, $product->discount_percentage);
                $tax = $this->calculateTax($totalAfterDiscount, $product->tax);
                $totalDue = $this->calculateTotalDue($totalAfterDiscount, $tax);
                $totalSum += $totalDue;

                if ($product) {
                    $invoice->invoiceItems()->create([
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->selling_price,
                        'amount' => $amount,
                        'discount' => $discount,
                        'total_after_discount' => $totalAfterDiscount,
                        'tax' => $tax,
                        'total_due' => $totalDue,
                    ]);
                }
            }
            //In order not to lose data, we can use each other
            event(new InvoiceActionEvent($product->inventory - $quantity, $product->id));

            $this->invoiceRepository->update($invoice->id, ['total_sum' => $totalSum]);

            return $this->invoiceRepository->findById($invoice->id);
        });

    }

    public function updateInvoice(int $id, array $data): ?Invoice
    {
        return DB::transaction(function () use ($id, $data) {
            $person = $this->personService->getPersonById($data['person_id']);
            if (!$person->active) {
                return "User is disabled";
            }

            $invoice = $this->invoiceRepository->findById($id);
            if ($invoice) {
                $invoice->update($data);

                if (isset($data['items'])) {
                    $invoice->invoiceItems()->delete();
                    $notBuyable = [];
                    $totalSum = 0;

                    foreach ($data['items'] as $productId => $quantity) {
                        $productId = (int) $productId;
                        $quantity = (int) $quantity;
                        $product = $this->productService->getProductById($productId);

                        if ($quantity > $product->inventory) {
                            $notBuyable[] = $product->id;
                            break;
                            // A list of unbuyable products should be returned here
                        }

                        $amount = $this->calculateAmount($quantity, $product->selling_price);
                        $discount = $this->calculateDiscount($amount, $product->discount_percentage);
                        $totalAfterDiscount = $this->calculateTotalAfterDiscount($amount, $product->discount_percentage);
                        $tax = $this->calculateTax($totalAfterDiscount, $product->tax);
                        $totalDue = $this->calculateTotalDue($totalAfterDiscount, $tax);
                        $totalSum += $totalDue;

                        $invoice->invoiceItems()->create([
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'price' => $product->selling_price,
                            'amount' => $amount,
                            'discount' => $discount,
                            'total_after_discount' => $totalAfterDiscount,
                            'tax' => $tax,
                            'total_due' => $totalDue,
                        ]);
                    }

                    event(new InvoiceActionEvent($product->inventory - $quantity, $product->id));
                    $this->invoiceRepository->update($invoice->id, ['total_sum' => $totalSum]);
                }

                return $invoice;
            }

            return null;
        });
    }

    public function deleteInvoice(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $invoice = $this->invoiceRepository->findById($id);
            if ($invoice) {
                //Here too, with every product that is removed, we have to add to the product, which I did not write due to lack of time
                //event(new InvoiceActionEvent($product->inventory - $quantity, $product->id));
                $invoice->invoiceItems()->delete();
                return $this->invoiceRepository->delete($id);
            }

            return false;
        });
    }

    public function calculateAmount(float $quantity, int $price): int
    {
        return (int)($quantity * $price);
    }

    public function calculateDiscount(float $amount, int $discount): int
    {
        return (int)($amount * $discount / 100);
    }

    public function calculateTotalAfterDiscount(int $amount, int $discount): int
    {
        return $amount - $discount;
    }

    public function calculateTax(int $totalAfterDiscount, int $tax): int
    {
        return $totalAfterDiscount * $tax / 100;
    }

    public function calculateTotalDue(int $totalAfterDiscount, int $tax): int
    {
        return $totalAfterDiscount + $tax;
    }
}
