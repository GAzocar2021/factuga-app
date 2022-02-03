@extends('layouts.app')

@section('content')
<div class="x_panel">
    <div class="x_title">
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <section class="content purchase">
            <div class="row">
                <div class="col-md-12">
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                        <strong>{{ session()->get('success') }}</strong>
                        </div>
                    @endif
                    @if(session()->has('warning'))
                        <div class="alert alert-success">
                        <strong>{{ session()->get('warning') }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row flex-column-reverse flex-md-row">
                <div class="col-md-6 col-sm-12">
                    <div class="purchase-header">
                        <h1>
                            <i class="fa fa-globe"></i> Factura #{{ $purchase->invoice->number }} - Cód. Producto {{ $purchase->product->code }}
                            <br>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4><strong>Información de la Compra</strong></h4>
                </div>
                <div class="col-md-6">
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <strong>Cantidad comprada del Producto:</strong><br />
                                <strong>Costo del Producto:</strong><br />
                                <strong>Impuesto del Producto:</strong><br />
                                <strong>Total A Pagar del Producto:</strong>
                            </td>
                            @php
                                $subtotal = bcdiv($purchase->quantity * $purchase->amount, '1', 2);
                                $tax = bcdiv($purchase->product->tax_perc / 100, '1', 2);
                                $stwt = bcdiv($subtotal * $tax, '1', 2);
                                $total = bcdiv($subtotal + $stwt, '1', 2);
                            @endphp
                            <td>
                                {{ $purchase->quantity }}<br />
                                $ {{ $purchase->amount }}<br />
                                {{ $purchase->product->tax_perc }}%<br />
                                $ {{ $total, '1', 2) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
