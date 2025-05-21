<?php

use App\Enums\ParticipantStatus;
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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hike_id')->constrained()->cascadeOnDelete();
            $table->foreignId('association_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('city');
            $table->boolean('is_student')->default(false);
            $table->unsignedSmallInteger('age');
            $table->string('phone');
            $table->unsignedSmallInteger('number')->default(0);
            $table->time('start_time')->default('00:00:00');
            $table->time('finish_time')->default('00:00:00');
            $table->time('completion_time')->default('00:00:00');
            $table->string('tshirt', 5)->default('No');
            $table->decimal('entry_fee', 10);
            $table->enum('status', array_map(fn ($status) => $status->value, ParticipantStatus::cases()))->default(ParticipantStatus::default());
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
