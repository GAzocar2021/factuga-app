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
              <h2><i class="fa fa-file-text"></i> Listado De Mis Compras</h2>
              <div class="title_right">
                <div style="float: right;">
                  <a href="{{ route('purchases.create') }}" class="btn btn-success" type="button" style="float: right;">
                    Nueva Compra
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
            <h3>Facturadas</h3>
            <div class="x_content">
              <div class="card-box table-responsive">
                <table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
                  <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Factura #</th>
                        <th>Nombre del Producto</th>
                        <th>Cantidad del Producto</th>
                        <th>Precio Unitario</th>
                        <th>Total Cobrado con Impuesto</th>
                        <th>Acciones</th>
                    </tr>
                  </thead>

                  <tbody>
                    @foreach($purchases as $key => $item)
                    @php
                        $i = $key + 1;
                    @endphp
                      <tr>
                        <td class="text-center" width="5%">{{ $i }}</td>
                        <td class="text-center" width="15%">{{ $item->invoice->number }}</td>
                        <td class="text-center" width="59%">{{ $item->product->name }}</td>
                        <td class="text-center" width="5%">{{ $item->quantity }}</td>
                        <td class="text-center" width="5%">$ {{ $item->amount }}</td>
                        <td class="text-center" width="5%">$ {{ bcdiv($item->product->price * $item->quantity, '1', 2) }}</td>
                        <td class="text-center" width="5%">
                            @if($item->invoice->status == 'PAGADO')
                            <span class="label label-success">FACTURA PAGADA</span>
                            @elseif($item->invoice->status == 'PENDIENTE')
                            <span class="label label-info">FACTURA PENDIENTE</span>
                            @else
                            <span class="label label-danger">FACTURA CANCELADA</span>
                            @endif
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
    <div class="">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <h3>Pendiente por Facturar</h3>
              <div class="x_content">
                <div class="card-box table-responsive">
                  <table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
                    <thead>
                      <tr>
                          <th>Nº</th>
                          <th>Nombre del Producto</th>
                          <th>Cantidad del Producto</th>
                          <th>Precio Unitario</th>
                          <th>Total Cobrado con Impuesto</th>
                          <th>Acciones</th>
                      </tr>
                    </thead>

                    <tbody>
                      @foreach($pendings as $key => $item)
                      @php
                          $i = $key + 1;
                      @endphp
                        <tr>
                          <td class="text-center" width="5%">{{ $i }}</td>
                          <td class="text-center" width="59%">{{ $item->product->name }}</td>
                          <td class="text-center" width="5%">{{ $item->quantity }}</td>
                          <td class="text-center" width="5%">$ {{ $item->amount }}</td>
                          <td class="text-center" width="5%">$ {{ bcdiv($item->product->price * $item->quantity, '1', 2) }}</td>
                          <td class="text-center" width="5%">
                              <ul class="nav navbar-nav navbar-right">
                                  <li class="">
                                      <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                      <span class=" fa fa-navicon"></span>
                                      </a>
                                      <ul style="width: 30px;" class="dropdown-menu dropdown-usermenu pull-right">
                                          <li><a href="{{ route('purchases.edit', $item->id) }}"><i class="fa fa-edit"></i> Editar Cantidad</a></li>
                                          <li><a data-toggle="modal" data-target=".modP{{$item->id}}"><i class="fa fa-trash-o"></i> Eliminar de la Compra</a></li>
                                      </ul>
                                  </li>
                              </ul>
                          </td>
                          <div class="modal fade modP{{$item->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                              <div class="modal-dialog modal-sm">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h4 class="modal-title" id="myModalLabel2">¿Esta seguro?</h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                      </div>
                                      <div class="modal-footer">
                                          <form action="{{ route('purchases.destroyPurchase', [$item->id, 1]) }}" method="POST">
                                              @csrf
                                              @method('delete')
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                              <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i> Borrar</button>
                                          </form>
                                      </div>
                                  </div>
                              </div>
                          </div>
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
