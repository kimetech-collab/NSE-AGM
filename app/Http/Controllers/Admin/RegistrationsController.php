<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RegistrationsController extends Controller
{
    public function index(Request $request)
    {
        $q = Registration::query();
        if ($search = $request->input('q')) {
            $q->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('payment_status')) {
            $q->where('payment_status', $status);
        }

        if ($request->filled('is_member')) {
            $q->where('is_member', (bool) $request->boolean('is_member'));
        }

        if ($request->filled('date_from')) {
            $q->whereDate('registration_timestamp', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $q->whereDate('registration_timestamp', '<=', $request->date_to);
        }

        $perPage = (int) $request->input('per_page', 25);
        $items = $q->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        return view('admin.registrations.index', ['registrations' => $items]);
    }

    public function show(int $id)
    {
        $registration = Registration::findOrFail($id);
        return view('admin.registrations.show', ['registration' => $registration]);
    }

    public function update(Request $request, int $id)
    {
        $registration = Registration::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'is_member' => 'nullable|boolean',
            'membership_number' => 'nullable|string|max:64',
        ]);

        $data['is_member'] = $request->boolean('is_member');

        $registration->update($data);

        return redirect()
            ->route('admin.registrations.show', $registration->id)
            ->with('success', 'Registration updated.');
    }

    public function export(Request $request)
    {
        $q = Registration::query();
        if ($search = $request->input('q')) {
            $q->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($status = $request->input('payment_status')) {
            $q->where('payment_status', $status);
        }
        if ($request->filled('is_member')) {
            $q->where('is_member', (bool) $request->boolean('is_member'));
        }
        if ($request->filled('date_from')) {
            $q->whereDate('registration_timestamp', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $q->whereDate('registration_timestamp', '<=', $request->date_to);
        }

        $rows = $q->orderBy('created_at', 'desc')->limit(2000)->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'name' => $r->name,
                'email' => $r->email,
                'is_member' => $r->is_member ? 'Yes' : 'No',
                'membership_number' => $r->membership_number,
                'status' => $r->payment_status,
                'registered_at' => optional($r->registration_timestamp)->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        $format = $request->input('format', 'csv');
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('pdf.registrations', ['rows' => $rows]);

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="registrations.pdf"',
            ]);
        }

        $csv = "id,name,email,is_member,membership_number,status,registered_at\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map(function ($v) { return '"'.str_replace('"', '""', (string) $v).'"'; }, $row))."\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="registrations.csv"',
        ]);
    }
}
