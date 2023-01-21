<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->string('id_reserv');
            $table->primary('id_reserv');
            $table->int('id_appart');
            $table->date('date_reserv');
            $table->int('id_reserv');
            $table->int('nb_adlt');
            $table->int('nb_enf_');
            $table->int('mont_reserv');
            $table->date('dat_arriv');
            $table->date('date_dep');
            $table->boolean('statut')->default(false);
            $table->int('nb_jr');
            $table->time('hr_reserv');
            $table->string('bojet');
            $table->int('rem');
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
        Schema::dropIfExists('reservations');
    }
}
