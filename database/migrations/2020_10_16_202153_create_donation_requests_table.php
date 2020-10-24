<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDonationRequestsTable extends Migration {

	public function up()
	{
		Schema::create('donation_requests', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('patient_name');
			$table->integer('patient_age');
			$table->string('hospital_name');
			$table->integer('blood_type_id')->unsigned();
			$table->string('num_of_blood_bags');
			$table->string('hospital_address');
			$table->integer('city_id')->unsigned();
			$table->string('patient_phone');
			$table->text('notes');
			$table->integer('client_id')->unsigned();
			$table->decimal('latitude', 10,8);
			$table->decimal('longitude', 10,8);
		});
	}

	public function down()
	{
		Schema::drop('donation_requests');
	}
}