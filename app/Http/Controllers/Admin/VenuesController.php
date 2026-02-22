<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VenueItem;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class VenuesController extends Controller
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function index()
    {
        if (! Schema::hasTable('venue_items')) {
            return view('admin.venues.index', ['venueItems' => collect()])
                ->with('error', 'Venue table not found. Run migrations.');
        }

        return view('admin.venues.index', [
            'venueItems' => VenueItem::query()->ordered()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.venues.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'section' => 'required|string|max:120',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string|max:5000',
            'meta' => 'nullable|string|max:5000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $meta = null;
        if (! blank($data['meta'] ?? null)) {
            $meta = array_values(array_filter(array_map('trim', explode("\n", $data['meta']))));
        }

        $item = VenueItem::create([
            'section' => $data['section'],
            'title' => $data['title'],
            'content' => $data['content'] ?? null,
            'meta' => $meta,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('venue.created', 'VenueItem', $item->id, $item->toArray());
        }

        return redirect()->route('admin.venues.index')->with('success', 'Venue item created.');
    }

    public function edit(VenueItem $venueItem)
    {
        return view('admin.venues.edit', compact('venueItem'));
    }

    public function update(Request $request, VenueItem $venueItem)
    {
        $data = $request->validate([
            'section' => 'required|string|max:120',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string|max:5000',
            'meta' => 'nullable|string|max:5000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $before = $venueItem->toArray();

        $meta = null;
        if (! blank($data['meta'] ?? null)) {
            $meta = array_values(array_filter(array_map('trim', explode("\n", $data['meta']))));
        }

        $venueItem->update([
            'section' => $data['section'],
            'title' => $data['title'],
            'content' => $data['content'] ?? null,
            'meta' => $meta,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('venue.updated', 'VenueItem', $venueItem->id, ['title' => $venueItem->title], $before, $venueItem->fresh()->toArray());
        }

        return redirect()->route('admin.venues.index')->with('success', 'Venue item updated.');
    }

    public function destroy(VenueItem $venueItem)
    {
        $before = $venueItem->toArray();
        $id = $venueItem->id;
        $venueItem->delete();

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('venue.deleted', 'VenueItem', $id, $before);
        }

        return redirect()->route('admin.venues.index')->with('success', 'Venue item deleted.');
    }
}
