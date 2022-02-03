@extends('layouts.app')

@section('styles')
  <!-- Datatables -->
  <link href="{{ asset('/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('content')
  <!-- page content -->
    <div class="">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2><i class="fa fa-file-text"></i> Listado De Facturas Emitidas</h2>
              <div class="title_right">
                <div style="float: right;">
                  <a href="{{ route('invoices.pending') }}" class="btn btn-info" type="button" style="float: right;">
                    Facturas Pendientes
                  </a>
                  <a href="{{ route('invoices.create') }}" class="btn btn-success" type="button" style="float: right;">
                    Crear Factura
                  </a>
                </div>
              </div>
              <div class="clearfix"></div>
              @if(session()->has('success'))
                <div class="alert alert-success">
                  <strong>{{ session()->get('success') }}</strong>
                </div>
              @endif
              @if(session()->has('warning'))
                <div class="alert alert-warning">
                  <strong>{{ session()->get('warning') }}</strong>
                </div>
              @endif
            </div>
            <div class="x_content">
              <div class="card-box table-responsive">
                <table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
                  <thead>
                    <tr>
                        <th>N° Factura</th>
                        <th>Cliente</th>
                        <th>Fecha Creación</th>
                        <th>Estado</th>
                        <th>Fecha Pagado</th>
                        <th>Fecha Cancelado</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                  </thead>

                  <tbody>
                    @foreach($invoices as $invoice)
                      <tr>
                        <td class="text-center">
                            <a href="{{ route('invoices.show', $invoice->id) }}">
                                {{ $invoice->number }}
                            </a>
                        </td>
                        <td class="text-center">{{ $invoice->user->name }}</td>
                        <td class="text-center">{{ $invoice->date ?? '' }}</td>
                        <td class="text-center" width="5%">
                            @if ($invoice->status == 'PENDIENTE')
                                <span class="label label-info">PENDIENTE</span>
                            @elseif($invoice->status == 'PAGADO')
                                <span class="label label-success">PAGADO</span>
                            @elseif($invoice->status == 'CANCELADO')
                                <span class="label label-danger">CANCELADO</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $invoice->date_pay ?? '' }}</td>
                        <td class="text-center">{{ $invoice->cancel_date ? date('d/m/Y', strtotime($invoice->cancel_date)) : '' }}</td>
                        <td class="text-center">$ {{ $invoice->amount }}</td>
                        <td class="text-center" width="5%">
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info"><i class="fa fa-eye"></i>  Ver</a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <!-- /page content -->
@endsection

@section('script')
  <!-- Datatables -->
  <script src="{{ asset('/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
  <script src="{{ asset('/vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
  <script src="{{ asset('/vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
  <script src="{{ asset('/vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('/vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
  <script src="{{ asset('/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
  <script src="{{ asset('/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
  <script src="{{ asset('/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
  <script src="{{ asset('/vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
  <script src="{{ asset('/vendors/jszip/dist/jszip.min.js') }}"></script>
  <script src="{{ asset('/vendors/pdfmake/build/pdfmake.min.js') }}"></script>
  <script src="{{ asset('/vendors/pdfmake/build/vfs_fonts.js') }}"></script>
@endsection
