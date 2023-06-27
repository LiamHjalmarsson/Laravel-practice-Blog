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
        
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            
            // create the colum and the key the key mateser it will now to build the kye in usch a way  where this table that your referecing and 
            // this colum 
            $table->foreignId('user_id')->constrained();
            // Here we create the coulm ourself and the key 
            $table->unsignedBigInteger('followeduser');
            $table->foreign('followeduser')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};