<?php

use App\Supports\Constant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckpointsTable extends Migration
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
        Schema::create('checkpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable();
            $table->string('type');
            $table->string('phone');
            $table->string('name');
            $table->string('address');
            $table->string('post_code')->nullable();
            $table->string('remark')->nullable();
            $table->enum('fallback', array_keys(Constant::ENABLED_OPTIONS))
                ->default(Constant::ENABLED_OPTION)->nullable();
            $table->enum('enabled', array_keys(Constant::ENABLED_OPTIONS))
                ->default(Constant::ENABLED_OPTION)->nullable();
            $table->foreignId('city_id')->index()->nullable();
            $table->foreignId('state_id')->index()->nullable();
            $table->foreignId('country_id')->index()->nullable();
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
        Schema::dropIfExists('checkpoints');

        //Temporary Disable Foreign Key Constraints
        Schema::enableForeignKeyConstraints();
    }
}
