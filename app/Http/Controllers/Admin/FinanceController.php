<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $query = PaymentTransaction::query()->with('registration');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        if ($q = trim((string) $request->input('q'))) {
            $query->where(function ($builder) use ($q) {
                $builder->where('provider_reference', 'like', "%{$q}%")
                    ->orWhereHas('registration', function ($r) use ($q) {
                        $r->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(50)->withQueryString();
        return view('admin.finance.index', ['transactions' => $items]);
    }

    public function export(Request $request)
    {
        $query = PaymentTransaction::query()->with('registration');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }
        if ($q = trim((string) $request->input('q'))) {
            $query->where(function ($builder) use ($q) {
                $builder->where('provider_reference', 'like', "%{$q}%")
                    ->orWhereHas('registration', function ($r) use ($q) {
                        $r->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        $rows = $query->orderBy('created_at', 'desc')->limit(5000)->get()->map(function (PaymentTransaction $t) {
            return [
                'id' => $t->id,
                'reference' => $t->provider_reference,
                'registrant_name' => $t->registration->name ?? '',
                'registrant_email' => $t->registration->email ?? '',
                'amount' => number_format($t->amount_cents / 100, 2, '.', ''),
                'currency' => $t->currency,
                'status' => $t->status,
                'created_at' => optional($t->created_at)->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        $format = $request->input('format', 'csv');
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('pdf.finance-transactions', ['rows' => $rows]);
            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="finance-transactions.pdf"',
            ]);
        }

        $csv = "id,reference,registrant_name,registrant_email,amount,currency,status,created_at\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map(fn ($v) => '"'.str_replace('"', '""', (string) $v).'"', $row))."\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="finance-transactions.csv"',
        ]);
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
