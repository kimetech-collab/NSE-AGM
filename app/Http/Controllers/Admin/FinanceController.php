<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    protected PaymentService $payments;

    public function __construct(PaymentService $payments)
    {
        $this->payments = $payments;
    }

    public function index(Request $request)
    {
        $items = PaymentTransaction::orderBy('created_at', 'desc')->paginate(50);
        return view('admin.finance.index', ['transactions' => $items]);
    }

    public function refund(Request $request, $id)
    {
        $tx = PaymentTransaction::findOrFail($id);
        // Call PaymentService to initiate refund
        $result = $this->payments->initiateRefund($tx);

        if (! empty($result['success']) && $result['success']) {
            // Record audit log
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'refund_initiated',
                'ip_address' => $request->ip(),
                'meta' => [
                    'transaction_id' => $tx->id,
                    'provider_reference' => $tx->provider_reference,
                    'amount_cents' => $tx->amount_cents,
                    'paystack_response' => $result['data'] ?? null,
                ],
            ]);

            \Log::info('Refund initiated', ['tx_id' => $tx->id, 'ref' => $tx->provider_reference]);
            return redirect()->back()->with('success', 'Refund initiated successfully.');
        }

        $msg = $result['message'] ?? 'Refund failed';
        \Log::warning('Refund failed', ['tx_id' => $tx->id, 'ref' => $tx->provider_reference, 'message' => $msg, 'response' => $result]);
        return redirect()->back()->with('error', 'Refund failed: ' . $msg);
    }
}
