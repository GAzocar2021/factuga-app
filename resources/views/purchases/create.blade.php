@extends('layouts.app')

@section('styles')
    <!-- Bootstrap-wysiwyg -->
    <link href="{{ asset('/vendors/google-code-prettify/bin/prettify.min.css') }}" rel="stylesheet">
    <!-- Select2 -->
    <link href="{{ asset('/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <!-- Switchery -->
    <link href="{{ asset('/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">
    <!-- Starrr -->
    <link href="{{ asset('/vendors/starrr/dist/starrr.css') }}" rel="stylesheet">

    <style>
      .select2 {
        width:100%!important;
      }
    </style>
@endsection

@section('content')
<form id="demo-form" method="POST" action="{{ route('purchases.store') }}" data-parsley-validate>
  @csrf
  <input type="hidden" name="user_id" value="{{ $user }}">
  <input type="hidden" name="pending" value="{{ $existPending }}">
  <input type="hidden" name="invoice_id" value="{{ $invoice->id ?? '' }}">
  <div class="x_panel">
    <div class="x_title">
      <h2><i class="fa fa-cubes"></i> Nueva Compra</h2>
      <div class="clearfix"></div>
      @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
      @endif
      @if(session()->has('warning'))
        <div class="alert alert-warning">
            {{ session()->get('warning') }}
        </div>
      @endif
    </div>
    <div class="x_content">
      <div class="" role="tabpanel" data-example-id="togglable-tabs">
        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
          <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Compra</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
          <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
            <!-- start form for validation -->
            <span class="clearfix"></span>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="product_id">Producto* :</label>
                            <select id="product_id" name="product_id" class="form-control select2 @error('product_id') parsley-error @enderror" value="{{ old('product_id') }}" required data-parsley-trigger="change">
                                <option value="" selected>Seleccionar...</option>
                                @foreach ($products as $item)
                                <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <span class="parsley-required red" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="quantity">Cantidad* :</label>
                            <input type="number" id="quantity" class="form-control @error('quantity') parsley-error @enderror" value="1" name="quantity" required />
                            @error('quantity')
                                <span class="parsley-required red" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="tax">Impuesto %:</label>
                            <input type="text" name="tax" id="tax" class="form-control @error('tax') parsley-error @enderror" value="0" name="tax" readonly />
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="amount">Total sin Impuesto:</label>
                            <input type="text" name="amount" id="amount" class="form-control @error('amount') parsley-error @enderror" value="0" name="amount" readonly />
                        </div>
                    </div>
                </div>

            <br/>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="actionBar">
                <button type="submit" class="btn btn-primary float-rigth">Guardar</button>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection

@section('script')
    <!-- Bootstrap-wysiwyg -->
    <script src="{{ asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js') }}"></script>
    <script src="{{ asset('/vendors/jquery.hotkeys/jquery.hotkeys.js') }}"></script>
    <script src="{{ asset('/vendors/google-code-prettify/src/prettify.js') }}"></script>
    <!-- jQuery Tags Input -->
    <script src="{{ asset('/vendors/jquery.tagsinput/src/jquery.tagsinput.js') }}"></script>
    <!-- Switchery -->
    <script src="{{ asset('/vendors/switchery/dist/switchery.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- Parsley -->
    <script src="{{ asset('/vendors/parsleyjs/dist/parsley.min.js') }}"></script>
    <!-- Autosize -->
    <script src="{{ asset('/vendors/autosize/dist/autosize.min.js') }}"></script>
    <!-- jQuery Autocomplete -->
    <script src="{{ asset('/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js') }}"></script>
    <!-- Starrr -->
    <script src="{{ asset('/vendors/starrr/dist/starrr.js') }}"></script>
    <script>
        let $select = document.getElementById('product_id');

        jQuery(document).ready(function($){
            $(document).ready(function() {
                $('#product_id').select2();

                $('#product_id').on('change', function () {
                    let productId = $('#product_id').val();
                    let url = "../products/" + productId + "/detail";

                    $.get(url, function( data ) {
                        let quantity = $('#quantity').val();
                        let price = 0;
                        let tax = data.tax_perc

                        price = data.cost * quantity;

                        $("#tax").val(tax.toFixed(2));
                        $("#amount").val(price.toFixed(2));
                    });
                });
            });
        });
    </script>
@endsection
