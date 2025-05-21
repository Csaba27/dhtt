<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('short_name');
            $table->text('name');
            $table->year('year');
            $table->date('date');
            $table->string('location');
            $table->unsignedSmallInteger('entry_fee');
            $table->unsignedSmallInteger('discount1');
            $table->unsignedSmallInteger('discount2');
            $table->dateTime('registration_start');
            $table->dateTime('registration_end');
            $table->dateTime('registration_discount_until');
            $table->string('organizer_name');
            $table->string('organizer_email');
            $table->string('organizer_phone');
            $table->text('invitation');
            $table->text('description');
            $table->text('rules');
            $table->unsignedTinyInteger('status')->default(0);
            $table->boolean('active')->default(false);
            $table->boolean('show')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
