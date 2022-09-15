<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('orderNumber');
            $table->string('orderReference');
            $table->integer('branchId');
            $table->integer('quotationId');
            $table->integer('clientId');
            $table->string('visitorNumber');
            $table->integer('csoId');
            $table->string('csoRemarks');
            $table->string('ticketFlag');
            $table->string('fullName');
            $table->string('email');
            $table->string('mobile');
            $table->string('hotelName');
            $table->string('hotelAddress');
            $table->string('roomNumber');
            $table->integer('totalAdult');
            $table->integer('totalChild');
            $table->integer('totalInfant');
            $table->date('tourDateFrom');
            $table->date('tourDateTo');
            $table->time('pickupTime');
            $table->float('totalPrice');
            $table->enum('isTourplan', [0, 1])->default(0);
            $table->text('tourplanRemarks');
            $table->string('tourplanAgentPrice');
            $table->string('tourplanAgentCode');
            $table->string('tourplanAgentName');
            $table->string('tourplanAgentReference');
            $table->enum('hasTransfer', [0, 1])->default(0);
            $table->enum('hasHotel', [0, 1])->default(0);
            $table->integer('activityCount')->default(0);
            $table->string('priceCurrency');
            $table->decimal('promoPrice', $precision = 10, $scale = 2)->default(0.00);
            $table->decimal('totalCost', $precision = 10, $scale = 2)->default(0.00);
            $table->string('paymentMode');
            $table->string('paymentStatus')->default(0.00);
            $table->dateTime('paymentDateTime', $precision = 0);
            $table->string('assignTo');
            $table->string('paypalTransactionId');
            $table->dateTime('addedDtm', $precision = 0);
            $table->dateTime('lastUpdated', $precision = 0);
            $table->enum('isDeleted', ['Y', 'N'])->default('N');
            $table->string('deliveryType');
            $table->string('deliveryOption');
            $table->string('deliveryTime');
            $table->date('deliveryDate');
            $table->string('flightNo');
            $table->string('arrivingFrom');
            $table->date('arrivalDateTime');
            $table->time('arrivalFlightTime');
            $table->string('dropOff');
            $table->integer('vehicleId');
            $table->string('vehicleName');
            $table->string('remarkTitle');
            $table->string('remarks');
            $table->string('invoice');
            $table->string('source');
            $table->integer('invoiceFlag');
            $table->enum('status', ['pending', 'confirm', 'new', 'cancel', 'success', 'in_process']);
            $table->string('bookingStatus');
            $table->string('globaltixOrderid');
            $table->text('rwsVisualID');
            $table->string('agent');
            $table->string('actionBy');
            $table->string('deliveryStatus');
            $table->string('tempId');
            $table->text('onlineLink');
            $table->integer('tourplanBookingId');
            $table->string('tourplanReference');
            $table->string('togoReference');
            $table->string('itineraryCode');
            $table->integer('tourplanHotelId');
            $table->string('tourplanReferenceNumber');
            $table->integer('promoId');
            $table->integer('eventId');
            $table->string('paymentGateway');
            $table->string('cluster');
            $table->string('agentCode');
            $table->integer('assignedOperatorId')->default(-1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
