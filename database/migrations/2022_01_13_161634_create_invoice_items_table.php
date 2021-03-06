<?php

use App\Supports\Constant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateInvoiceItemsTable extends Migration
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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->double('quantity', 7, 3);
            $table->string('weight')->nullable();
            $table->string('dimension')->nullable();
            $table->double('rate', 15, 4)->default(1);
            $table->double('subtotal', 15, 4)->default(0);
            $table->string('discount')->nullable();
            $table->string('tax')->nullable();
            $table->double('total', 15, 4);
            $table->json('item_json')->nullable();
            $table->enum('enabled', array_keys(Constant::ENABLED_OPTIONS))
                  ->default(Constant::ENABLED_OPTION)->nullable();
            $table->foreignId('created_by')->index()->nullable();
            $table->foreignId('updated_by')->index()->nullable();
            $table->foreignId('deleted_by')->index()->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });

        //Temporary Disable Foreign Key Constraints
        Schema::enableForeignKeyConstraints();
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
        Schema::dropIfExists('invoice_items');

        //Temporary Disable Foreign Key Constraints
        Schema::enableForeignKeyConstraints();
    }
}
