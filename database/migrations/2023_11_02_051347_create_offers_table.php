<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();       // provide created_at and updated_at columns

            $table->foreignIdFor(       // relationship with listing
                \App\Models\Listing::class, 
                'listing_id'
            )->constrained('listings');
            $table->foreignIdFor(       // relationship with users (bidder)
                \App\Models\User::class, 
                'bidder_id'
            )->constrained('users');

            $table->unsignedInteger('amount');      // offer amount (only accept positive value)

            $table->timestamp('accepted_at')->nullable();       // provide accepted_at column (null means not accepted, date is exist means accepted)
            $table->timestamp('rejected_at')->nullable();       // provide rejected_at column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
