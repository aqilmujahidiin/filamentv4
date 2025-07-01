<?php

// app/Services/MidtransService.php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Payment;

class MidtransService
{
    protected string $serverKey;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');

        Config::$serverKey = $this->serverKey;
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    public function createSnapUrl(Payment $payment): string
    {
        $orderId = 'LES-' . $payment->id . '-' . time();

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $payment->payment_amount,
            ],
            'customer_details' => [
                'first_name' => $payment->student->name,
                'email' => $payment->student->email,
            ],
            'item_details' => $payment->schedules->map(fn($schedule) => [
                'id' => "SCH-{$schedule->id}",
                'price' => $schedule->pivot->amount,
                'quantity' => 1,
                'name' => 'Kelas ' . $schedule->course->name ?? 'Les Privat',
            ])->toArray(),
        ];

        $snap = Snap::createTransaction($payload);

        return $snap->redirect_url;
    }

    public function isSignatureValid(array $payload): bool
    {
        $orderId = (string) $payload['order_id'] ?? '';
        $statusCode = (string) $payload['status_code'] ?? '';
        $grossAmount = (string) $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';

        $expected = hash('sha512', "{$orderId}{$statusCode}{$grossAmount}{$this->serverKey}");

        return $expected === $signatureKey;
    }
}
