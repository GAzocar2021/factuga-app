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
        $pendings = PurchasePending::with(['user', 'product'])->where('user_id', $userId)->orderBy('id', 'desc')->get();

        $invoice = Invoice::with('user')->where('user_id', $userId)->latest('number')->first();
        $purchases = Purchase::with(['product', 'invoice'])->where('invoice_id', $invoice->id)->orderBy('id', 'desc')->get();

        // dd($pendings, $invoice, $purchases);

        return view('purchases.index', [
            'purchases' => $purchases,
            'pendings' => $pendings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $existPPending = false;
        $userId = Auth::user()->id;

        $products = Product::where('is_active', 'ACTIVO')->get();
        $invoice = Invoice::with(['user', 'purchases'])->where('user_id', $userId)->where('status', 'PENDIENTE')->latest('number')->first();

        if (!$invoice) {
            $existPPending = true;
        }

        return view('purchases.create', [
            'products' => $products,
            'user' => $userId,
            'invoice' => $invoice,
            'existPending' => $existPPending
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
        if ($request['pending'] == 0) {
            $purchase = new Purchase();

            $purchase->invoice_id = $request->invoice_id;
            $purchase->product_id = $request->product_id;
            $purchase->quantity = $request->quantity;
            $purchase->amount = $request->amount;
            $purchase->save();

            $invoice = Invoice::findOrFail($request->invoice_id);

            $total = $invoice->amount + ($request->amount * $request->quantity);

            $invoice->amount = $total;
            $invoice->save();
        } else {
            PurchasePending::create($request->all());
        }

        Product::discountStock($request->product_id, $request->quantity);

        return redirect(route('purchases.index'))->with('success', 'Producto Comprado');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pending = PurchasePending::findOrFail($id);

        return view('purchases.edit', [
            'purchase' => $pending
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
        $purchase = PurchasePending::findOrFail($id);

        $purchase->quantity = $purchase->quantity + $request->quantity;
        $purchase->save();

        Product::discountStock($purchase->product_id, $request->quantity);

        return redirect(route('purchases.index'))->with('success', 'Compra Actualizada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $status)
    {
        $total = 0;

        if ($status == '1') {
            $purchase = PurchasePending::findOrFail($id);
        } else {
            $purchase = Purchase::with(['product', 'invoice'])->findOrFail($id);
            $invoice = Invoice::findOrFail($purchase->invoice_id);

            $price = $invoice->amount - ($purchase->product->price * $purchase->quantity);

            $invoice->amount = $price;
            $invoice->save();
        }

        $product = Product::findOrFail($purchase->product_id);

        $total = $product->stock + $purchase->quantity;

        $product->stock = $total;
        $product->save();

        $purchase->delete();
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
