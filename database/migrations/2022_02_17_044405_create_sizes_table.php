<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('size_name', 255);
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();
        });

        DB::table('sizes')->insert(
            array(
                [
                    'size_name' => "Nhỏ",
                ],
                [
                    'size_name' => "Lớn",
                ]
            )

        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sizes');
    }
}