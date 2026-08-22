<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slot_local_churches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('slot_id')
                ->constrained('slots')
                ->cascadeOnDelete();

            $table->string('local_church');

            $table->unsignedInteger('seat_count')->default(0);

            $table->timestamps();

            $table->unique([
                'slot_id',
                'local_church',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_local_churches');
    }
};
