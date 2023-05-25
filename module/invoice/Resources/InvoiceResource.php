<?php


namespace INVOICE\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'item_name' => $this->item_name,
            'active' => $this->active,
            'selling_price' => $this->selling_price,
            'tax' => $this->tax,
            'discount_percentage' => $this->discount_percentage,
            'inventory' => $this->inventory,
        ];
    }
}
