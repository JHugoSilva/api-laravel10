<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\InvoiceResource;
use App\Models\Invoice;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update']);
    }
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return (new Invoice())->filter($request);
        // return InvoiceResource::collection(Invoice::with('user')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|max:1',
            'paid' => 'required|numeric|between:0,1',
            'payment_date' => 'nullable',
            'value' => 'required|numeric|between:1,9999.99'
        ]);

        if ($validator->fails()) {
            return $this->error('Data invalid', 422, $validator->errors());
        }

        $created = Invoice::create($validator->validated());

        if ($created) {
            return $this->response('Invoice created', 201, new InvoiceResource($created->load('user')));
        }
        return $this->error('Invoice not created', 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = Invoice::find($id);

        if ($invoice) {
            return new InvoiceResource($invoice);
        }
        return $this->response('Invoice not found', 400);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|max:1|in:'.implode(',', ['B', 'C', 'P']),
            'paid' => 'required|numeric|between:0,1',
            'payment_date' => 'nullable|date_format:Y-m-d H:i:s',
            'value' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation invalid', 422, $validator->errors());
        }

        $validated = $validator->validated();

        $invoice = Invoice::find($id);
        $updated = $invoice->update([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'paid' => $validated['paid'],
            'value' => $validated['value'],
            'payment_date' => $validated['paid'] ? $validated['payment_date'] : null,
        ]);

        if ($updated) {
            return $this->response('Invoice updated', 200, new InvoiceResource($invoice->load('user')));
        }

        return $this->error('Invoice not update', 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = Invoice::find($id);
        $delete = $invoice->delete();

        if ($delete) {
            return $this->response('Invoice deleted', 200);
        }
        return $this->response('Invoice not deleted', 400);
    }
}
