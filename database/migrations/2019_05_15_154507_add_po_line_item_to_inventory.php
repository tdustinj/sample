<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoLineItemToInventory extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('inventory', function (Blueprint $table) {
      $table->integer('fk_purchase_order_item')->unsigned()->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('purchase', function (Blueprint $table) {
      $table->dropColumn('fk_purchase_order_item');
    });
  }
}
