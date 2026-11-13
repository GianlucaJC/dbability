<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('print_price_cents')->default(4500)->after('price_cents');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('purchase_type', 16)->default('digital')->after('product_id');
            $table->string('shipping_name')->nullable()->after('status');
            $table->string('shipping_address_line1')->nullable()->after('shipping_name');
            $table->string('shipping_address_line2')->nullable()->after('shipping_address_line1');
            $table->string('shipping_city')->nullable()->after('shipping_address_line2');
            $table->string('shipping_state')->nullable()->after('shipping_city');
            $table->string('shipping_postal_code')->nullable()->after('shipping_state');
            $table->string('shipping_country', 2)->nullable()->after('shipping_postal_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'purchase_type',
                'shipping_name',
                'shipping_address_line1',
                'shipping_address_line2',
                'shipping_city',
                'shipping_state',
                'shipping_postal_code',
                'shipping_country',
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('print_price_cents');
        });
    }
};
