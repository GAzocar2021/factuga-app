<?php

namespace Database\Seeders;

use App\Models\Invoice;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invoice = [
            [
                'user_id' => 2,
                'number' => '00001001',
                'amount' => 332.28,
                'date' => '29/01/2022',
                'date_pay' => '29/01/2022',
                'status' => 'PAGADO',
            ],
            [
                'user_id' => 3,
                'number' => '00001002',
                'amount' => 250.00,
                'date' => '29/01/2022',
                'cancel_date' => '2022-01-29 16:05:18',
                'cancel_status' => 'SUCCESS',
                'status' => 'CANCELADO',
            ],
            [
                'user_id' => 2,
                'number' => '00001003',
                'amount' => 105.00,
                'date' => '29/01/2022',
                'date_pay' => '29/01/2022',
                'status' => 'PAGADO',
            ],
            [
                'user_id' => 4,
                'number' => '00001004',
                'amount' => 184.46,
                'date' => '29/01/2022',
                'date_pay' => '29/01/2022',
                'status' => 'PAGADO',
            ],
        ];

        foreach ($invoice as $key => $value) {
            Invoice::create($value);
        }
    }
}
