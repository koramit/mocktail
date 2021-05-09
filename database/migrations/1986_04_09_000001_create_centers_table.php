<?php

use App\Models\Center;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centers', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name')->unique();
            $table->string('name_short', 100)->index();
            $table->boolean('active')->default(true);
            $table->unsignedInteger('creator_id')->index()->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        $datetime = now();
        Center::insert([
            ['name' => 'ศิริราช', 'name_short' => 'SI', 'created_at' => $datetime, 'updated_at' => $datetime],
            ['name' => 'ปิยมหาราชการุณย์', 'name_short' => 'SiPH', 'created_at' => $datetime, 'updated_at' => $datetime],
            ['name' => 'ศูนย์การแพทย์กาญจนาภิเษก', 'name_short' => 'GJ', 'created_at' => $datetime, 'updated_at' => $datetime],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('centers');
    }
}
