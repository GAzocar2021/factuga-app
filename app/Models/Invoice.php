<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'number',
        'amount',
        'date',
        'cancel_date',
        'cancel_status',
        'date_pay',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public static function getAmountInvoice($invoice_id) {
        $purchases = Purchase::select('quantity', 'amount')->where('invoice_id', $invoice_id)->get();
        $subTotal = 0;
        $sumaAmount = 0;

        foreach ($purchases as $value){
            $subTotal = intval($value->quantity) * $value->amount;
            $sumaAmount += ($subTotal * $value->product->tax_perc / 100) + ($subTotal);
        }

        return round($sumaAmount, 2);
    }

    public static function getInvoicePending() {
        $invoicesNumber = [];
        $invoices = Invoice::where('status', 'PENDIENTE')->get();

        foreach($invoices as $invoice){
            array_push($invoicesNumber, [
                'number' => $invoice->number,
                'amount' => $invoice->amount,
                'date' => $invoice->date
            ]);
        }

        return $invoicesNumber;
    }
}
