<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Enums\ScheduleStatusEnum;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\SchedulePayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreatePaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create payment when there are 8 completed schedules with the same course_id, student_id, and teacher_id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to check for completed schedules...');

        // Mendapatkan grup schedule yang completed dengan course_id, student_id, dan teacher_id yang sama
        // Filter hanya untuk bulan dan tahun saat ini
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $this->info("Filtering schedules for month: {$currentMonth}, year: {$currentYear}");

        $scheduleGroups = Schedule::where('status', ScheduleStatusEnum::COMPLETED)
            ->whereDoesntHave('payments') // Hanya ambil schedule yang belum memiliki payment
            ->whereMonth('date', $currentMonth) // Filter berdasarkan bulan saat ini
            ->whereYear('date', $currentYear) // Filter berdasarkan tahun saat ini
            ->select('course_id', 'student_id', 'teacher_id', DB::raw('count(*) as total'))
            ->groupBy('course_id', 'student_id', 'teacher_id')
            ->having('total', '>=', 5)
            ->get();

        if ($scheduleGroups->isEmpty()) {
            $this->info('No eligible schedule groups found.');
            return 0;
        }

        $this->info('Found ' . $scheduleGroups->count() . ' eligible schedule groups.');

        foreach ($scheduleGroups as $group) {
            $this->processScheduleGroup($group);
        }

        $this->info('Payment creation process completed.');
        return 0;
    }

    /**
     * Process a group of schedules to create payment
     */
    private function processScheduleGroup($group)
    {
        $this->info("Processing group: Course #{$group->course_id}, Student #{$group->student_id}, Teacher #{$group->teacher_id}");

        // Ambil 8 schedule yang completed dan belum memiliki payment
        // Filter hanya untuk bulan dan tahun saat ini
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $schedules = Schedule::where([
            'course_id' => $group->course_id,
            'student_id' => $group->student_id,
            'teacher_id' => $group->teacher_id,
            'status' => ScheduleStatusEnum::COMPLETED
        ])
            ->whereDoesntHave('payments')
            ->whereMonth('date', $currentMonth) // Filter berdasarkan bulan saat ini
            ->whereYear('date', $currentYear) // Filter berdasarkan tahun saat ini
            ->limit(5) // Ubah limit menjadi 8 sesuai dengan deskripsi
            ->get();

        // Hitung total amount dari semua schedule
        $totalAmount = $schedules->sum(fn($schedule) => $schedule->calculateFee());

        DB::beginTransaction();
        try {
            // Buat payment baru
            $payment = Payment::create([
                'student_id' => $group->student_id,
                'payment_method' => null, // Akan diisi saat pembayaran dilakukan
                'payment_date' => null, // Akan diisi saat pembayaran dilakukan
                'total_amount' => $totalAmount,
                'payment_status' => PaymentStatus::Unpaid,
                'payment_note' => 'Auto-generated payment for 8 completed schedules',
                'created_by' => 1, // Ganti dengan user ID yang sesuai atau gunakan sistem
            ]);

            // Buat schedule_payment untuk setiap schedule
            foreach ($schedules as $schedule) {
                SchedulePayment::create([
                    'payment_id' => $payment->id,
                    'schedule_id' => $schedule->id,
                    'amount' => $schedule->calculateFee(),
                ]);
            }

            DB::commit();
            $this->info("Created payment #{$payment->id} with total amount: {$totalAmount}");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error creating payment: {$e->getMessage()}");
        }
    }
}