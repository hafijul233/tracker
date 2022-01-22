<?php

use App\Supports\Constant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Temporary Disable Foreign Key Constraints
        Schema::disableForeignKeyConstraints();

        //Table Structure
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->dateTime('invoiced_at')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->json('user_info')->nullable();
            $table->foreignId('receiver_id')->nullable();
            $table->json('receiver_info')->nullable();
            $table->foreignId('pickup_checkpoint_id')->nullable();
            $table->foreignId('destination_checkpoint_id')->nullable();
            $table->double('subtotal', 15, 4)->default(0);
            $table->string('discount')->nullable();
            $table->string('tax')->nullable();
            $table->double('total', 15, 4);
            $table->enum('enabled', array_keys(Constant::ENABLED_OPTIONS))
                            ->default(Constant::ENABLED_OPTION)->nullable();
            $table->foreignId('created_by')->index()->nullable();
            $table->foreignId('updated_by')->index()->nullable();
            $table->foreignId('deleted_by')->index()->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Temporary Disable Foreign Key Constraints
        Schema::disableForeignKeyConstraints();

        //Remove Table Structure
        Schema::dropIfExists('invoices');

        //Temporary Disable Foreign Key Constraints
        Schema::enableForeignKeyConstraints();
    }
}
