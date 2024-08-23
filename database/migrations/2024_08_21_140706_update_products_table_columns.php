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
            
            $table->string('nutriscore_score')->nullable()->change();

            
            $table->string('serving_quantity')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('nutriscore_score')->nullable()->change();

            $table->decimal('serving_quantity', 10, 2)->nullable()->change();
        });
    }
};
