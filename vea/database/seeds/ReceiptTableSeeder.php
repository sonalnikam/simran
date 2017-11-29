<?php

use Illuminate\Database\Seeder;
use App\Receipt;

class ReceiptTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Receipt::create(['cash_receipt' => 0,'cheque_receipt'=>0]); 
    }
}
