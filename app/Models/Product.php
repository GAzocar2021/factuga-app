<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'stock',
        'alert_stock',
        'cost',
        'price',
        'tax_perc',
        'image',
        'is_active'
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function purchasesPending()
    {
        return $this->hasMany(PurchasePending::class);
    }

    /**
     * Generate folio for product
     *
     * @return string $code
     */
    public static function generateFolio()
    {
        $count = Product::count();
        $code = str_pad($count + 1, 8, '0', STR_PAD_LEFT);

        if ($count > 0) {
            if(!self::existsCode($code)) {
                return str_pad($code, 8, '0', STR_PAD_LEFT);
            }

            $codeLast = Product::orderBy('id', 'DESC')->select('code')->take(1)->get();
            $code = str_pad($codeLast[0]->code + 1, 8, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    /**
     * Verify that the code exists
     *
     * @param string $code
     * @return bool
     */
    public static function existsCode($code) : bool
    {
        $isExists = Product::where('code', $code)->count();

        return ($isExists > 0) ? true : false;
    }

    /**
     * Decrement the stock of product
     *
     * @param integer $id
     * @param integer $qty
     * @return void
     */
    public static function subtractStock($id, $qty)
    {
        $product = Product::where('is_active', 'ACTIVO')->findOrFail($id);
        $tmp = $product->stock - $qty;

        if ($product->stock > 0 && $tmp >= 0) {
            $product->stock = $tmp;
            $product->save();

            return true;
        }

        return false;
    }

    /**
     * Check which product reached its minimum stock
     *
     * @return bool
     */
    public static function checkMinStock() : bool
    {
        $alert = false;
        $products = Product::where('is_active', 'ACTIVO')->get();

        foreach($products as $product){
            if($product->stock <= $product->alert_stock){
                $alert = true;
            }
        }

        return $alert;
    }

    public static function getProductMinStock()
    {
        $productsName = [];
        $products = Product::where('is_active', 'ACTIVO')->get();

        foreach($products as $product){
            if($product->stock <= $product->alert_stock){
                array_push($productsName, [
                    'name' => $product->name,
                    'code' => $product->code,
                    'stock' => $product->stock
                ]);
            }
        }

        return $productsName;
    }

    private function discountStock($id, $qty)
    {
        $total = 0;
        $product = Product::findOrFail($id);

        $total = $product->stock - $qty;
        $product->stock = $total;

        $product->save();

        return true;
    }
}
