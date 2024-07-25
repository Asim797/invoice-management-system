<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::with('customer')->when(!empty($request->s), function($query) use($request){
            $query->where('id', 'LIKE', '%'.$request->s.'%');
        })->orderBy('id','desc')->get();
        return response()->json(['invoices' => $invoices], 200);
    }

    public function show($id)
    {
        $invoice = Invoice::with(['customer', 'invoice_items.product'])->find($id);
        if (!empty($invoice)){
            return response()->json(['invoice' => $invoice], 200);
        }
        return response()->json(['invoice' => null], 404);
    }

    public function create()
    {
        $counter = Counter::where('key', 'invoice')->first();
        $invoice = Invoice::orderBy('id','desc')->first();
        if (!empty($invoice)){
            $invoiceCount = $invoice->id+1;
            $counters = $counter->value+$invoiceCount;
        }else{
            $counters = $counter->value+1;
        }

        $formData = [
            'number' => $counter->prefix.$counters,
            'customer_id' => null,
            'customer' => null,
            'date' => date('Y-m-d'),
            'due_date' => null,
            'reference' => null,
            'discount' => 0,
            'toc' => 'Default terms and conditions',
            'items' => [
                [
                    'product_id' => null,
                    'product' => null,
                    'unit_price' => 0,
                    'quantity' => 1,
                ]
            ]
        ];
        return response()->json($formData, 200);
    }

    public function addInvoice(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $invoice = Invoice::create($data);
            foreach (json_decode($data['invoice_items'], true) as $item){
                $item['invoice_id'] = $invoice->id;
                InvoiceItem::create($item);
            }
            DB::commit();
            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $exception){
            DB::rollBack();
            return response()->json(['status' => 'error'], 200);
        }
    }

    public function updateInvoice(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $invoice = Invoice::find($id);
            if (!empty($invoice)){
                $invoice->update($data);
                InvoiceItem::where('invoice_id', $invoice->id)->delete();
                foreach (json_decode($data['invoice_items'], true) as $item){
                    $item['invoice_id'] = $invoice->id;
                    InvoiceItem::create($item);
                }
                DB::commit();
                return response()->json(['status' => 'success'], 200);
            }
        } catch (\Exception $exception){
            DB::rollBack();
            return response()->json(['status' => 'error'], 200);
        }
    }

    public function deleteInvoice($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->invoice_items()->delete();
        $invoice->delete($id);
        return response()->json(['status' => 'success'], 200);
    }

    public function deleteInvoiceItem($id)
    {
        $invoiceItem = InvoiceItem::findOrFail($id);
        $invoiceItem->delete($id);
        return response()->json(['status' => 'success'], 200);
    }
}
