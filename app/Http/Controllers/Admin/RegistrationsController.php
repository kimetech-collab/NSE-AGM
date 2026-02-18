<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationsController extends Controller
{
    public function index(Request $request)
    {
        $q = Registration::query();
        if ($search = $request->input('q')) {
            $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
        }

        $perPage = (int) $request->input('per_page', 25);
        $items = $q->orderBy('created_at', 'desc')->paginate($perPage);
        return view('admin.registrations.index', ['registrations' => $items]);
    }

    public function show(int $id)
    {
        $registration = Registration::findOrFail($id);
        return view('admin.registrations.show', ['registration' => $registration]);
    }

    public function export(Request $request)
    {
        // stub: export CSV of filtered registrations
        $rows = Registration::limit(1000)->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'name' => $r->name,
                'email' => $r->email,
                'status' => $r->payment_status,
            ];
        })->toArray();

        $csv = "id,name,email,status\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map(function ($v) { return '"'.str_replace('"', '""', $v).'"'; }, $row))."\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="registrations.csv"',
        ]);
    }
}
