<?php

namespace INVOICE\Controller\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use INVOICE\Requests\CreateInvoiceRequest;
use INVOICE\Requests\UpdateInvoiceRequest;
use INVOICE\Service\v1\InvoiceServiceInterface;

class InvoiceController extends Controller
{
    private $invoiceService;

    public function __construct(InvoiceServiceInterface $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index(): JsonResponse
    {
        $invoices = $this->invoiceService->getAllInvoices();
        return response()->json($invoices, 200);
    }


    public function store(CreateInvoiceRequest $request): JsonResponse
    {
        $data = $request->validated();
        $invoice = $this->invoiceService->createInvoice($data);
        return response()->json($invoice, 201);
    }

    public function show(int $id): JsonResponse
    {
        $invoice = $this->invoiceService->getInvoice($id);
        return response()->json($invoice, 200);
    }


    public function update(UpdateInvoiceRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $invoice = $this->invoiceService->updateInvoice($id, $data);
        return response()->json($invoice, 200);
    }


    public function destroy(int $id): JsonResponse
    {
        $this->invoiceService->deleteInvoice($id);
        return response()->json(null, 204);
    }
}
