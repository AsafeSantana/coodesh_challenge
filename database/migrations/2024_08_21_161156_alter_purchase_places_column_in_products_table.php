<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->text('purchase_places')->change();
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->string('purchase_places', 255)->change();
    });
}
};
