<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchasePending;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::orderBy('id', 'desc')->get();
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $number = str_pad('0', 8, '0', STR_PAD_LEFT);

        $invoice = Invoice::last();
        $clients = PurchasePending::with(['user'])->get();

        $nextNumber = intval($invoice->number) + 1;
        $number = str_pad($nextNumber, 8, '0', STR_PAD_LEFT);

        return view('invoices.create', [
            'clients' => $clients,
            'number' => $number,
            'date' => date('d/m/Y')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvoiceRequest $request)
    {
        Invoice::create($request->all());

        $invoice = Invoice::last();
        $pendings = PurchasePending::where('user_id', $request['user_id'])->get();

        foreach ($pendings as $item) {
            $purchase = new Purchase();

            $purchase->invoice_id = $invoice->id;
            $purchase->product_id = $item->product_id;
            $purchase->quantity = $item->quantity;
            $purchase->amount = $item->amount;

            $purchase->save();

            Product::discountStock($item->product_id, $item->quantity);

            $item->delete();
        }

        return redirect(route('invoices.index'))->with('success', 'Factura Creada');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::with(['user','purchases'])->findOrFail($id);

        $totales = [];
        $subtotal = 0;
        $tax = 0;
        $amountWithTax = 0;
        $total = 0;

        foreach ($invoice->purchases as $item) {
            $sT = $item->quantity * $item->amount;
            $subtotal += bcdiv($sT, '1', 2);
            $tax += bcdiv($item->product->tax_perc, '1', 2);
            $perc = $item->product->tax_perc / 100;
            $amountWithTax += bcdiv($perc * $sT, '1', 2);
        }

        $totales['subtotal']        = $subtotal;
        $totales['tax']             = $tax;
        $totales['amountWithTax']   = $amountWithTax;
        $totales['total']           = $subtotal + $amountWithTax;

        // dd($invoice->purchases);
        return view('invoices.show', [
            'invoice' => $invoice,
            'totales' => $totales
        ]);
    }

    public function pendingList()
    {
        $invoices = Invoice::where('status', 'PENDIENTE')->get();
        // dd($invoices);
        return view('invoices.pending', compact('invoices'));
    }

    public function markPaid($id, $action)
    {
        $invoice = Invoice::findOrFail($id);
        $action = strtoupper($action);

        $invoice->status = $action;
        $invoice->date_pay = date('d/m/Y');
        $invoice->save();

        $action = ucfirst($action);

        return redirect(route('invoices.show', $invoice->id))->with('success', "Marcado como {$action}");
    }

    /**
     * Cancel of Invoice
     *
     * @param [type] $id
     * @param [type] $action
     * @return void
     */
    public function cancel($id)
    {
        $invoice = Invoice::where('status', 'PENDIENTE')->findOrFail($id);

        // Cancel in plataform
        $invoice->cancel_date = date('Y-m-d H:i:s');
        $invoice->status = 'CANCELADO';
        $invoice->cancel_status = 'SUCCESS';
        $invoice->save();

        $purchases = Purchase::where('invoice_id', $invoice->id)->get();

        $product = null;

        foreach ($purchases as $item) {
            $total = 0;

            $product = Product::findOrFail($item->id);

            $total = $product->stock + $item->quantity;

            $product->stock = $total;
            $product->save();
        }

        return redirect(route('invoices.index'))->with('success', "Cancelación de factura #{$invoice->number} exitosa");
    }
}
