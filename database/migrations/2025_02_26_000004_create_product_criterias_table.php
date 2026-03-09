<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_criterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained()->cascadeOnDelete();
            $table->decimal('value', 8, 2);
            $table->timestamps();

            $table->unique(['product_id', 'criteria_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_criterias');
    }
};
