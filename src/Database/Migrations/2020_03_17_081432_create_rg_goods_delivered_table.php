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
            $table->date('date');
            $table->time('time');
            $table->unsignedBigInteger('contact_id');
            $table->string('contact_name', 50);
            $table->string('contact_address', 50);
            $table->string('reference', 100)->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('status', 20)->nullable();
            $table->unsignedTinyInteger('sent')->nullable();
            $table->unsignedBigInteger('salesperson_id')->nullable();
            $table->morphs('itemable');
            $table->string('contact_notes', 250)->nullable();
            $table->string('items_model', 250)->nullable()->default('Rutatiina\GoodsDelivered\Models\GoodsDeliveredItem');
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
