<?php

use App\Models\Ability;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('login')->unique();
            $table->string('password');
            $table->json('profile');
            $table->unsignedSmallInteger('center_id')->constrained('centers')->onDelete('cascade');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('abilities', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name')->unique();
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name')->unique();
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('ability_role', function (Blueprint $table) {
            $table->primary(['role_id', 'ability_id']);
            $table->unsignedSmallInteger('role_id')->constrained('roles')->onDelete('cascade');
            $table->unsignedSmallInteger('ability_id')->constrained('abilities')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->primary(['user_id', 'role_id']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedSmallInteger('role_id')->constrained('roles')->onDelete('cascade');
            $table->timestamps();
        });

        $datetime = ['created_at' => now(), 'updated_at' => now()];
        Ability::insert([
            ['name' => 'refer_case'] + $datetime, // on behalf of center
            ['name' => 'create_note'] + $datetime, // md:[admit, DC], nurse:[nurse]
            ['name' => 'view_cases'] + $datetime, // scope by center
            ['name' => 'create_patient'] + $datetime, // add SI HN to patient
            ['name' => 'admit_patient'] + $datetime, // add AN to case
            ['name' => 'grant_admin'] + $datetime, // assign admin for user
            ['name' => 'grant_user'] + $datetime, // assign center, md and nurse for user
            ['name' => 'grant_teammate'] + $datetime, // assign referer for teammate
        ]);

        Role::insert([
            ['name' => 'root'] + $datetime,
            ['name' => 'admin'] + $datetime,
            ['name' => 'referer'] + $datetime,
            ['name' => 'md'] + $datetime,
            ['name' => 'nurse'] + $datetime,
            ['name' => 'center'] + $datetime,
        ]);

        $assignment = [
            'root' => ['grant_admin', 'grant_user', 'view_cases', 'create_patient', 'admit_patient'],
            'admin' => ['grant_user', 'view_cases', 'create_patient', 'admit_patient'],
            'referer' => ['refer_case', 'view_cases'],
            'md' => ['refer_case', 'create_note', 'view_cases', 'create_patient', 'admit_patient'],
            'nurse' => ['create_note', 'view_cases', 'create_patient', 'admit_patient'],
            'center' => ['grant_teammate'],
        ];

        foreach ($assignment as $role => $abilities) {
            $theRole = Role::whereName($role)->first();
            foreach ($abilities as $abilitie) {
                $theRole->allowTo($abilitie);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('ability_role');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('abilities');
        Schema::dropIfExists('users');
    }
}
