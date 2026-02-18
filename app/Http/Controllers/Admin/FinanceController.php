<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FinanceController extends Controller
{
    protected PaymentService $payments;
    protected AuditService $audit;

    public function __construct(PaymentService $payments, AuditService $audit)
    {
        $this->payments = $payments;
        $this->audit = $audit;
    }

    public function index(Request $request)
    {
        $items = PaymentTransaction::orderBy('created_at', 'desc')->paginate(50);
        return view('admin.finance.index', ['transactions' => $items]);
    }

    public function refund(Request $request, $id)
    {
        $tx = PaymentTransaction::findOrFail($id);
        
        // Store before state
        $beforeState = $tx->toArray();
        
        // Call PaymentService to initiate refund
        $result = $this->payments->initiateRefund($tx);

        if (! empty($result['success']) && $result['success']) {
            // Refresh transaction data
            $tx->refresh();
            $afterState = $tx->toArray();
            
            // Record audit log with AuditService
            $this->audit->logRefund('initiated', $tx->id, [
                'transaction_id' => $tx->id,
                'provider_reference' => $tx->provider_reference,
                'amount_cents' => $tx->amount_cents,
                'amount_naira' => $tx->amount_cents / 100,
                'paystack_response' => $result['data'] ?? null,
                'initiated_by' => Auth::user()->name ?? 'System',
            ], $beforeState, $afterState);

            \Log::info('Refund initiated', ['tx_id' => $tx->id, 'ref' => $tx->provider_reference, 'by' => Auth::id()]);
            return redirect()->back()->with('success', 'Refund initiated successfully.');
        }

        $msg = $result['message'] ?? 'Refund failed';
        
        // Log failed refund attempt
        $this->audit->logFailure('refund.initiated', 'PaymentTransaction', $tx->id, $msg, [
            'transaction_id' => $tx->id,
            'provider_reference' => $tx->provider_reference,
            'amount_cents' => $tx->amount_cents,
            'paystack_response' => $result,
        ]);
        
        \Log::warning('Refund failed', ['tx_id' => $tx->id, 'ref' => $tx->provider_reference, 'message' => $msg, 'response' => $result, 'by' => Auth::id()]);
        return redirect()->back()->with('error', 'Refund failed: ' . $msg);
    }
}
