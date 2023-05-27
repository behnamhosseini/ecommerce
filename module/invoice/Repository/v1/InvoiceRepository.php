<?php


namespace INVOICE\Repository\v1;


use INVOICE\Models\Invoice;

class InvoiceRepository  implements InvoiceRepositoryInterface
{
    public function getAll()
    {
        return Invoice::with('invoiceItems')->get();
    }

    public function findById(int $id): ?Invoice
    {
        return Invoice::with('invoiceItems')->find($id);
    }

    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $invoice = Invoice::find($id);
        if ($invoice) {
            return $invoice->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $invoice = Invoice::find($id);
        if ($invoice) {
            $invoice->invoiceItems()->delete();
            return $invoice->delete();
        }

        return false;
    }

    public function attachItems(Invoice $invoice,$data) :void
    {
        $invoice->invoiceItems()->create($data);
    }
}
