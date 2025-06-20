<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('status')->nullable();

            $table->foreignId('course_id')->nullable()->constraints()->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constraints()->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constraints()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constraints('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constraints('users')->nullOnDelete();
            $table->timestamps();

            $table->index([
                'course_id',
                'student_id',
                'teacher_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
