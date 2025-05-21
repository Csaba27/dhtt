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
        Schema::create('hikes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hike_type_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->year('year');
            $table->text('route');
            $table->string('track_link')->nullable();
            $table->unsignedSmallInteger('distance');
            $table->decimal('time_limit');
            $table->string('elevation');
            $table->unsignedSmallInteger('number_start');
            $table->unsignedSmallInteger('number_end');
            $table->unsignedSmallInteger('number_start_extra')->default(0)->nullable();
            $table->unsignedSmallInteger('number_end_extra')->default(0)->nullable();
            $table->unsignedSmallInteger('current_number')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hikes');
    }
};
