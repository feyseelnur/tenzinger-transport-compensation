<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommutesTable extends Migration
{
    public function up(): void
    {
        Schema::create('commutes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('transport_id')->constrained()->onDelete('cascade');
            $table->float('distance');
            $table->integer('workdays_per_week');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commutes');
    }
}
