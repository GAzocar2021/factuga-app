<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
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
        $products = Product::all();
        return view('products.index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create', [
            'folio' => Product::generateFolio()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        if ($this->checkRepeatingCode($request['code'])) {
            return back()->with('warning', 'El codigo del producto ya esta en uso');
        }

        if ($request->stock <= $request->alert_stock) {
            return back()->with('warning', 'El stock debe ser mayor a la alerta de stock');
        }

        $product = Product::create($request->all());

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $nameFile = time().'_'.$image->getClientOriginalName();
            Storage::disk('products')->put($nameFile, File::get($image));

            $image = Product::find($product->id);
            $image->image = $nameFile;
            $image->save();
        }

        return redirect(route('products.index'))->with('success', 'Producto Creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $produ = [];
        $produ['name'] = $product->name;
        $produ['description'] = $product->description;
        $produ['stock'] = $product->stock;
        $produ['cost'] = $product->cost;
        $produ['price'] = $product->price;
        $produ['tax_perc'] = $product->tax_perc;
        $produ['image'] = $product->image;
        $produ['is_active'] = $product->is_active;

        return view('products.show', [
            'product' => $produ
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('products.edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        if ($this->checkRepeatingCode($request['code'], $id)) {
            return back()->with('warning', 'El codigo del producto ya esta en uso');
        }

        if ($request->stock <= $request->alert_stock) {
            return back()->with('warning', 'El stock debe ser mayor a la alerta de stock');
        }

        if(!isset($request->is_active)) {
            $request['is_active'] = 'INACTIVO';
        } else {
            $request['is_active'] = 'ACTIVO';
        }

        $product = Product::findOrFail($id);
        $imgPrevius = $product->image;

        $product->update($request->all());

        if ($request->hasFile('image')) {
            //delete previous image
            if (Storage::disk('products')->exists($imgPrevius) && $imgPrevius != 'default.png') {
                Storage::disk('products')->delete($imgPrevius);
            }

            //update new image
            $image = $request->file('image');
            $nameFile = time().'_'.$image->getClientOriginalName();
            $product->image = $nameFile;
            $product->update();
            Storage::disk('products')->put($nameFile, File::get($image));
        }

        return redirect(route('products.index'))->with('success', 'Producto Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (Storage::disk('products')->exists($product->image) && $product->image != 'default.png') {
            Storage::disk('products')->delete($product->image);
        }

        $product->delete();

        return redirect(route('products.index'))->with('success', 'Producto Eliminado');
    }

    /**
     * Check if code the product repeats
     *
     * @param [type] $code
     * @return void
     */
    protected function checkRepeatingCode($code, $id = '')
    {
        $existFolio = Product::where('code', intval($code))
                        ->count();

        if($id != '') { // If the empty id is not empty, use the update
            $product = Product::findOrFail($id);
            $existFolio = ($product->code == $code) ? 0 : $existFolio;
        }

        return ($existFolio > 0) ? true : false;
    }
}
