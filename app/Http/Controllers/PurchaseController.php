<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchasePendingRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchasePending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::user()->id;
        $existPending = false;
        $pendings = PurchasePending::with(['user', 'product'])->where('user_id', $userId)->orderBy('id', 'desc')->get();

        if($pendings) {
            foreach ($pendings as $item) {
                $existPending = true;
            }
        }

        if (!$existPending) {
            $invoice = Invoice::with('user')->where('user_id', $userId)->where('status', 'PENDIENTE')->last();
            $purchases = Purchase::with(['product', 'invoice'])->where('invoice_id', $invoice->id)->orderBy('id', 'desc')->get();
        }

        return view('purchases.index', [
            'purchases' => ($existPending) ? $pendings : $purchases,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::where('is_active', 'ACTIVO')->get();

        return view('purchases.create', [
            'products' => $products,
            'folio' => Product::generateFolio()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchasePendingRequest $request)
    {
        PurchasePending::create($request->all());

        return redirect(route('purchases.index'))->with('success', 'Producto Comprado');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $status = 1)
    {
        $pending = PurchasePending::findOrFail($id);
        $purchase = Purchase::findOrFail($id);

        return view('purchases.edit', [
            'purchase' => ($status === 1) ? $pending : $purchase,
            'status' => $status
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PurchaseRequest $request, $id)
    {
        $status = $request["status"];

        if ($status === 1) {
            $purchase = PurchasePending::findOrFail($id);
        } else {
            $purchase = Purchase::findOrFail($id);
        }

        $purchase->update($request->all());

        return redirect(route('purchases.index'))->with('success', 'Compra Actualizada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchasePending  $pending
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchasePending $pending)
    {
        $total = 0;

        $product = Product::findOrFail($pending->id);

        $total = $product->stock + $pending->quantity;

        $product->stock = $total;
        $product->save();

        $pending->delete();
        return redirect()->route('purchases.index')->with('success', 'Compra Eliminada');
    }

    public function purchaseProduct($id, $number, $qty = 1)
    {
        $productFound = Product::findOrFail($id);
        $invoiceFound = Invoice::where('number', intval($number))->get();

        $existProduct = false;
        $existInvoice = false;
        $idInvoice = null;

        if ($invoiceFound) {
            foreach ($invoiceFound as $item) {
                $existInvoice = true;
            }
        }

        if (!$existInvoice) {
            $invoice = new Invoice();
            $invoice->user_id = Auth::user()->id;
            $invoice->number = str_pad($number, 8, '0', STR_PAD_LEFT);
            $invoice->amount = $productFound->price;
            $invoice->date = date('d/m/Y');
            $invoice->save();
        } else {
            foreach ($invoiceFound as $item) {
                $idInvoice = $item->id;
            }

            $invoice = Invoice::findOrFail($idInvoice);
            $amountTotal = $invoice->amount + ($productFound->price * $qty);

            $invoice->amount = $amountTotal;
            $invoice->save();
        }


        $productPurchase = Purchase::where('product_id', $productFound->id)->where('invoice_id', $idInvoice)->get();

        if ($productPurchase) {
            foreach ($productPurchase as $item) {
                $existProduct = true;
            }
        }

        if (!$existProduct) {
            $invoice = Invoice::where('status', 'PENDIENTE')->where('user_id', Auth::user()->id)->get();

            $purchase = new Purchase();
            $purchase->product_id = $id;
            $purchase->invoice_id = $invoice[0]->id;
            $purchase->quantity = $qty;
            $purchase->amount = $productFound->cost;
            $purchase->save();
        } else {
            $purchase = Purchase::findOrFail($productPurchase[0]->id);

            $total = $purchase->quantity + $qty;
            $purchase->quantity = $total;
            $purchase->save();
        }

        Product::discountStock($id, $qty);

        return redirect(route('purchases.index'))->with('success', "Producto {$productFound->code} agregado a su factura #{$number}");
    }
}
