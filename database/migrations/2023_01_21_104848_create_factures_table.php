<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->string('id_fact');
            $table->primary('id_fact');
            $table->int('mont_sur_fact');
            $table->string('id_reserv');
            $table->date('date_emi');
            $table->int('rest_a_pay');
            $table->time('hr_emi');
            $table->boolean('s');
            $table->int('id_pay');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factures');
    }
}
