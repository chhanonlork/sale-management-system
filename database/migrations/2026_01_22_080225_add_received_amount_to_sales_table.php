<?php
use Illuminate\Support\Facades\Schema; // 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            
            // ១. ឆែកមើល received_amount
            if (!Schema::hasColumn('sales', 'received_amount')) {
                $table->decimal('received_amount', 10, 2)->default(0)->after('total_amount');
            }

            // ២. ឆែកមើល change_amount
            if (!Schema::hasColumn('sales', 'change_amount')) {
                $table->decimal('change_amount', 10, 2)->default(0)->after('received_amount');
            }

            // ៣. ឆែកមើល payment_type (កន្លែងដែល Error)
            if (!Schema::hasColumn('sales', 'payment_type')) {
                $table->string('payment_type')->default('Cash')->after('change_amount');
            }
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['received_amount', 'change_amount', 'payment_type']);
        });
    }
};