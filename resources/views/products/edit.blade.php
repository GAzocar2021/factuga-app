@extends('layouts.app')

@section('styles')
    <!-- bootstrap-wysiwyg -->
    <link href="{{ asset('/vendors/google-code-prettify/bin/prettify.min.css') }}" rel="stylesheet">
    <!-- Switchery -->
    <link href="{{ asset('/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">
    <!-- starrr -->
    <link href="{{ asset('/vendors/starrr/dist/starrr.css') }}" rel="stylesheet">
    <!-- select2 -->
    <link href="{{ asset('/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<form id="product-form" method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" data-parsley-validate>
  @csrf
  @method('put')
  <div class="x_panel">
    <div class="x_title">
      <h2><i class="fa fa-cubes"></i> Actualizar Producto</h2>
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
          <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Datos del Producto</a>
          </li>
        </ul>
        <div id="myTabContent" class="tab-content">
          <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
            <!-- start form for validation -->
            <span class="clearfix"></span>
            <div class="row">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="center-block">
                            <div class="profile_img">
                                <div id="crop-avatar">
                                    @if(\Storage::disk('products')->exists("$product->image"))
                                      <img class="img-responsive avatar-view center-block" src="{{ asset('storage/products/'.$product->image) }}" width="200" id="previewlogo"  alt="Avatar" title="">
                                    @else
                                      <img class="img-responsive avatar-view center-block" src="{{ asset('storage/products/default.png') }}" width="200" id="previewlogo"  alt="Avatar" title="">
                                    @endif
                                    <label for="image">Imagen Producto :</label>
                                    <input type="file" name="image" class="@error('image') parsley-error @enderror" value="{{ old('image') }}" id="image">
                                        @error('image')
                                        <span class="invalid-feedback red" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </br>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="name">Nombre* :</label>
                            <input type="text" id="name" class="form-control @error('name') parsley-error @enderror" value="{{ $product->name }}" name="name" required />
                            @error('name')
                                <span class="invalid-feedback red" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="code">Código* :</label>
                            <input type="text" id="code" class="form-control @error('code') parsley-error @enderror" value="{{ $product->code }}" name="code" autocomplete="off" readonly />
                            @error('code')
                                <span class="invalid-feedback red" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for="description">Descripción* :</label>
                            <textarea name="description" id="description" class="form-control @error('description') parsley-error @enderror" cols="30" rows="5" required>{{ $product->description }}</textarea>
                            @error('description')
                                <span class="invalid-feedback red" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label for="cost">Costo Unitario* :</label>
                            <input type="text" id="cost" class="form-control @error('cost') parsley-error @enderror" name="cost" value="{{ $product->cost }}" data-parsley-trigger="change" required />
                            @error('cost')
                                <span class="invalid-feedback red" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label for="tax_perc">Impuesto %* :</label>
                            <input type="text" id="tax_perc" class="form-control @error('tax_perc') parsley-error @enderror" name="tax_perc" value="{{ $product->tax_perc }}" data-parsley-trigger="change" required />
                            @error('tax_perc')
                                <span class="invalid-feedback red" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label for="price">Precio* :</label>
                            <input type="text" id="price" class="form-control @error('price') parsley-error @enderror" name="price" value="{{ $product->price }}" data-parsley-trigger="change" readonly />
                            @error('price')
                                <span class="invalid-feedback red" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="stock">Stock* :</label>
                            <input type="text" id="stock" class="form-control @error('stock') parsley-error @enderror" name="stock" value="{{ $product->stock }}" data-parsley-trigger="change" required />
                            @error('stock')
                                <span class="invalid-feedback red" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="alert_stock">Alerta Stock*:</label>
                            <input type="text" id="alert_stock" name="alert_stock" class="form-control @error('alert_stock') parsley-error @enderror" data-parsley-trigger="change" value="{{ $product->alert_stock }}" required />
                            @error('alert_stock')
                                <span class="invalid-feedback red" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label>
                                <br>
                                <input type="checkbox" name="is_active" class="js-switch" @if($product->is_active == 'ACTIVO') checked @endif/> Activo
                            </label>
                            @error('is_active')
                                <span class="invalid-feedback red" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
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
    <!-- bootstrap-wysiwyg -->
    <script src="{{ asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js') }}"></script>
    <script src="{{ asset('/vendors/jquery.hotkeys/jquery.hotkeys.js') }}"></script>
    <script src="{{ asset('/vendors/google-code-prettify/src/prettify.js') }}"></script>
    <!-- jQuery Tags Input -->
    <script src="{{ asset('/vendors/jquery.tagsinput/src/jquery.tagsinput.js') }}"></script>
    <!-- Switchery -->
    <script src="{{ asset('/vendors/switchery/dist/switchery.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- Autosize -->
    <script src="{{ asset('/vendors/autosize/dist/autosize.min.js') }}"></script>
    <!-- jQuery autocomplete -->
    <script src="{{ asset('/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js') }}"></script>
    <!-- starrr -->
    <script src="{{ asset('/vendors/starrr/dist/starrr.js') }}"></script>
    <script>
        jQuery(document).ready(function($){
            $(document).ready(function() {
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
