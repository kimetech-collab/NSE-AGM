<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SponsorsController extends Controller
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function index()
    {
        if (! Schema::hasTable('sponsors')) {
            return view('admin.sponsors.index', ['sponsors' => collect()])
                ->with('error', 'Sponsors table not found. Run migrations.');
        }

        return view('admin.sponsors.index', [
            'sponsors' => Sponsor::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'nullable|string|max:1000',
            'logo_file' => 'nullable|image|max:3072',
            'website_url' => 'nullable|url|max:1000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $logoUrl = $data['logo_url'] ?? null;
        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('sponsors', 'public');
            $logoUrl = Storage::disk('public')->url($path);
        }

        $sponsor = Sponsor::create([
            'name' => $data['name'],
            'logo_url' => $logoUrl,
            'website_url' => $data['website_url'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('sponsor.created', 'Sponsor', $sponsor->id, $sponsor->toArray());
        }

        return redirect()->route('admin.sponsors.index')->with('success', 'Sponsor created.');
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'nullable|string|max:1000',
            'logo_file' => 'nullable|image|max:3072',
            'website_url' => 'nullable|url|max:1000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $before = $sponsor->toArray();
        $logoUrl = $data['logo_url'] ?? null;
        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('sponsors', 'public');
            $logoUrl = Storage::disk('public')->url($path);
        }

        $sponsor->update([
            'name' => $data['name'],
            'logo_url' => $logoUrl,
            'website_url' => $data['website_url'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log(
                'sponsor.updated',
                'Sponsor',
                $sponsor->id,
                ['name' => $sponsor->name],
                $before,
                $sponsor->fresh()->toArray()
            );
        }

        return redirect()->route('admin.sponsors.index')->with('success', 'Sponsor updated.');
    }

    public function destroy(Sponsor $sponsor)
    {
        $before = $sponsor->toArray();
        $id = $sponsor->id;
        $sponsor->delete();

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('sponsor.deleted', 'Sponsor', $id, $before);
        }

        return redirect()->route('admin.sponsors.index')->with('success', 'Sponsor deleted.');
    }
}
