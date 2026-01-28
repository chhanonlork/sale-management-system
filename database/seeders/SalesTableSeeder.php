<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesTableSeeder extends Seeder
{
    public function run()
    {
        // ១. បង្កើតអតិថិជនលេខ 105 ជាមុនសិន (ដើម្បីកុំឱ្យជាប់ Error Foreign Key)
        // ប្រើ insertOrIgnore ដើម្បីកុំឱ្យ Error បើមានឈ្មោះនេះរួចហើយ
        DB::table('customers')->insertOrIgnore([
            ['id' => 105, 'name' => 'Sok Dara', 'phone' => '012000111', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 108, 'name' => 'Som Nang', 'phone' => '012000222', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 102, 'name' => 'Chea Vichea', 'phone' => '012000333', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ២. បន្ទាប់មកទើបបញ្ចូលទិន្នន័យលក់
        DB::table('sales')->insert([
            [
                'id' => 1, 
                'user_id' => 1, 
                'customer_id' => NULL, 
                'invoice_number' => 'INV-2023001', 
                'total_amount' => 10.00, 
                'discount' => 0.00, 
                'tax' => 0.00, 
                'final_total' => 10.00, 
                'payment_type' => 'Cash', 
                'created_at' => '2023-10-25 08:30:00'
            ],
            [
                'id' => 2, 
                'user_id' => 1, 
                'customer_id' => 105, // ឥឡូវដាក់ 105 បានហើយ ព្រោះយើងបានបង្កើតខាងលើ
                'invoice_number' => 'INV-2023002', 
                'total_amount' => 25.00, 
                'discount' => 2.50, 
                'tax' => 0.00, 
                'final_total' => 22.50, 
                'payment_type' => 'QR', 
                'created_at' => '2023-10-25 09:15:00'
            ],
            // ... ដាក់ទិន្នន័យផ្សេងទៀត
        ]);
    }
}