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
<form id="purchase-form" method="POST" action="{{ route('purchases.update', $purchase->id) }}" data-parsley-validate>
  @csrf
  @if ($status === 1)
  <input type="hidden" name="user_id" value="{{ $purchase->user_id }}">
  @else
  <input type="hidden" name="invoice_id" value="{{ $purchase->invoice_id }}">
  @endif
  <input type="hidden" name="status" value="{{ $status }}">
  <div class="x_panel">
    <div class="x_title">
      <h2><i class="fa fa-cubes"></i> Editar Compra</h2>
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
                            <label for="name">Producto* :</label>
                            <input type="hidden" name="product_id" value="{{ $purchase->product_id }}">
                            <input type="text" id="name" class="form-control @error('name') parsley-error @enderror" value="{{ $purchase }}" name="name" readonly />
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

                let remove = () => {
                    for (let i = $select.options.length; i >= 0; i--) {
                        $select.remove(i);
                    }
                };

                let change = (data) => {
                    for(let x in data) {
                        let option = document.createElement('option');
                        option.value = data[x].id;
                        option.text = data[x].name;
                        $select.appendChild(option);
                    }
                };

                $("#image").on('change', function(e) {
                    let x = e.target;
                    if (!x.files || !x.files.length) {
                    return false;
                    }

                    let img = x.files[0];
                    const objectURL = URL.createObjectURL(img);
                    $("#previewlogo").attr('src',objectURL);
                });

                $("#cost, #tax_perc").keyup(function() {
                    let cost = $('#cost').val();
                    let tax = $('#tax_perc').val();
                    let price = 0;

                    price = cost * (1 + (tax / 100));
                    console.log(price);
                    $("#price").val(price.toFixed(2));
                });
            });
        });
    </script>
@endsection
