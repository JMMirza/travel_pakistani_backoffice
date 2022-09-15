<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationPinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitation_pins', function (Blueprint $table) {
            $table->id();
            $table->string('fName');
            $table->string('lName');
            $table->integer('groupId');
            $table->integer('orderId');
            $table->string('pinCode');
            $table->integer('shareOrder');
            $table->string('email');
            $table->string('username');
            $table->enum('joinStatus', ['N', 'Y'])->default('N');
            $table->integer('tourplanBookingId');
            $table->string('tourplanReference');
            $table->string('userType');
            $table->string('agentCode');
            $table->string('phoneNumber');
            $table->enum('invitationStatus', ['Sent', 'New'])->default('Sent');
            $table->date('joinDate');
            $table->string('qrCode');
            $table->tinyInteger('isTourplan');
            $table->string('orderReference');
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
        Schema::dropIfExists('invitation_pins');
    }
}
