<?php

namespace App\Http\Resources\v1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    private array $types = ['C' => 'Cartão', 'B' => 'Boleto', 'P' => 'Pix'];
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $paid = $this->paid;
        return [
            'user' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'created' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s') 
            ],
            'type' => $this->types[$this->type],
            'value' => 'R$ '.number_format($this->value, 2, ',', '.'),
            'paid' => $paid ? 'Pago' : 'Não Pago',
            'paymentDate' => $paid ? Carbon::parse($this->payment_date)->format('d/m/Y H:i:s') : Null,
            'paymentSince' => $paid ? Carbon::parse($this->payment_date)->diffForHumans() : Null,
        ];
    }
}
