<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Enums\PaymentStatusEnum;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function pay(Payment $payment, MidtransService $midtrans): RedirectResponse
    {
        $url = $midtrans->createSnapUrl($payment);

        $payment->update([
            'payment_status' => PaymentStatusEnum::Pending,
            'payment_date' => now(),
            'payment_note' => 'Waiting for payment via Midtrans',
        ]);

        return redirect($url);
    }

    public function handleWebhook(Request $request, MidtransService $midtrans)
    {
        $payload = $request->all();

        if (!$midtrans->isSignatureValid($payload)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $payload['order_id'] ?? '';
        $parts = explode('-', $orderId);
        $paymentId = $parts[1] ?? null;

        if (!$paymentId || !is_numeric($paymentId)) {
            return response()->json(['message' => 'Invalid order_id'], 400);
        }

        $payment = Payment::find($paymentId);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $txStatus = $payload['transaction_status'] ?? '';
        $fraud = $payload['fraud_status'] ?? null;
        $txType = strtoupper($payload['payment_type'] ?? '');
        $vaInfo = $payload['va_numbers'][0] ?? null;

        $payment->update([
            'payment_status' => match ($txStatus) {
                'settlement', 'capture' => PaymentStatusEnum::Paid,
                'expire', 'cancel', 'deny' => PaymentStatusEnum::Expired,
                'pending' => PaymentStatusEnum::Pending,
                default => $payment->payment_status,
            },
            'payment_method' => $txType,
            'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
            'va_number' => $vaInfo['va_number'] ?? null,
            'bank' => $vaInfo['bank'] ?? null,
            'expiry_time' => $payload['expiry_time'] ?? null,
            'payment_note' => null,
        ]);

        return response()->json(['message' => 'OK']);
    }
}
