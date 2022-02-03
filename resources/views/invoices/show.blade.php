@extends('layouts.app')

@section('content')
<div class="x_panel">
    <div class="x_title">
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <section class="content invoice">
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
                    <div class="invoice-header">
                        <h1>
                            <i class="fa fa-globe"></i> Factura #{{ $invoice->number }}
                            <br>
                        </h1>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    @if ($invoice->status != 'PAGADO' && $invoice->status != 'CANCELADO')
                    <a href="{{ route('invoices.cancel', $invoice->id) }}"  class="btn btn-app btn-xs" style="float: right;">
                        <i class="fa fa-remove"></i> CANCELAR
                    </a>
                    <a href="{{ route('invoices.mark', [$invoice->id, 'PAGADO']) }}" class="btn btn-app btn-xs" style="float: right;">
                        <i class="fa fa-check"></i> PAGAR
                    </a>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12 invoice-col">
                    <strong> Receptor </strong>
                    <address>
                        <br>Nombre: <strong>{{ $invoice->user->name }}</strong>
                    </address>
                </div>

                <div class="col-md-4 col-sm-4 invoice-col">
                    <strong> Emisor </strong>
                    <address>
                        <br>Nombre: <strong>FacturaGA</strong>
                    </address>
                </div>
            </div>

            <div class="card-box table-responsive">
                <table class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Código del Producto</th>
                            <th>Nombre Producto</th>
                            <th style="width: 59%">Descripción</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario sin Impuesto</th>
                            <th>Precio Unitario con Impuesto</th>
                            <th>Subtotal sin Impuesto Incluido</th>
                            <th>Subtotal con Impuesto Incluido</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->purchases as $item)
                        @php
                            $subTotal = $item->quantity * $item->amount;
                            $stWithTax = $subTotal * $item->product->tax_perc / 100;
                        @endphp
                        <tr>
                            <td>{{ $item->product->code }}</td>
                            <td>{{ $item->product->name}}</td>
                            <td>{{ $item->product->description }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>$ {{ $item->amount }}</td>
                            <td>$ {{ $item->product->price }}</td>
                            <td>$ {{ bcdiv($subTotal, '1', 2) }}</td>
                            <td>$ {{ bcdiv($stWithTax + $subTotal, '1', 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6 col-12 col-sm-12"></div>

                <div class="col-md-6 col-sm-12 col-xs-12">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th style="width:50%">Subtotal sin Impuesto:</th>
                                <td>$ {{ $totales['subtotal'] }}</td>
                            </tr>
                            <tr>
                                <th>Total de Impuestos Sumados:</th>
                                <td>{{ $totales['tax'] }}%</td>
                            </tr>
                            <tr>
                                <th>Total en Impuestos Cobrados:</th>
                                <td>$ {{ $totales['amountWithTax'] }}</td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td>$ {{ $totales['total'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4><strong>Información del Pago</strong></h4>
                </div>
                <div class="col-md-6">
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                @if ($invoice->date_pay)
                                <strong>Fecha de Pago :</strong><br />
                                @endif
                                <strong>Monto :</strong>
                            </td>
                            <td>
                                @if ($invoice->date_pay)
                                {{ $invoice->date_pay }}<br />
                                @endif
                                $ {{ $invoice->amount }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
