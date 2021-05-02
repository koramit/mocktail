<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refer_cases', function (Blueprint $table) {
            $table->id();
            $table->uuid('slug');
            $table->string('hn')->nullable();
            $table->string('patient_name', 1024)->nullable();
            $table->unsignedBigInteger('patient_id')->nullable()->constrained('patients')->onDelete('cascade');
            $table->unsignedBigInteger('admission_id')->nullable()->constrained('admissions')->onDelete('cascade');
            $table->unsignedBigInteger('note_id')->constrained('notes')->onDelete('cascade');
            $table->datetime('submitted_at')->nullable();
            $table->unsignedBigInteger('user_id')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('refer_cases');
    }
}
