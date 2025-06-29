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
        Schema::create('link_visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('link_id');
            $table->string('ip_address', 45);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Индекс и внешний ключ на таблицу links:
            $table->index('link_id');
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_visits');
    }
};
