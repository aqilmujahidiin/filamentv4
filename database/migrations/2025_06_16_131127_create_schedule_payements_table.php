<?php

use App\Models\Payment;
use App\Models\Schedule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedule_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Payment::class, 'payment_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Schedule::class, 'schedule_id')->nullable()->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_payments');
    }
};
