<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRgGoodsDeliveredTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenant')->create('rg_goods_delivered', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            //>> default columns
            $table->softDeletes();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            //<< default columns

            //>> table columns
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('parent_id');
            $table->string('external_key', 100);
            $table->unsignedBigInteger('app_id');
            $table->string('document_name', 50)->default('Goods Delivered Note');
            $table->string('number_prefix', 50)->nullable();
            $table->unsignedBigInteger('number');
            $table->unsignedTinyInteger('number_length');
            $table->string('number_postfix', 50)->nullable();
            $table->string('internal_ref_document', 20);
            $table->unsignedBigInteger('internal_ref_id');
            $table->unsignedBigInteger('txn_entree_id');
            $table->unsignedBigInteger('txn_type_id');
            $table->date('date');
            $table->time('time');
            $table->unsignedBigInteger('debit_financial_account_code')->nullable();
            $table->unsignedBigInteger('credit_financial_account_code')->nullable();
            $table->unsignedBigInteger('contact_id');
            $table->string('contact_name', 50);
            $table->string('contact_address', 50);
            $table->string('reference', 100)->nullable();
            $table->string('base_currency', 3);
            $table->string('quote_currency', 3);
            $table->unsignedDecimal('exchange_rate', 20,10);
            $table->unsignedDecimal('taxable_amount', 20,5);
            $table->unsignedDecimal('total', 20, 5);
            $table->boolean('balances_where_updated')->default(0); //$table->unsignedDecimal('balance', 20, 5);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->date('due_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status', 20)->nullable();
            $table->unsignedTinyInteger('sent')->nullable();
            $table->unsignedBigInteger('salesperson_id')->nullable();
            $table->string('payment_mode', 50)->nullable();
            $table->string('payment_terms', 100)->nullable();
            $table->string('contact_notes', 250)->nullable();
            $table->string('external_ref', 250)->nullable();
            $table->string('terms_and_conditions', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('rg_goods_delivered');
    }
}