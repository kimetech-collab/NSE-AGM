<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgrammeItem;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProgrammesController extends Controller
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function index()
    {
        if (! Schema::hasTable('programme_items')) {
            return view('admin.programme.index', ['programmeItems' => collect()])
                ->with('error', 'Programme table not found. Run migrations.');
        }

        return view('admin.programme.index', [
            'programmeItems' => ProgrammeItem::query()
                ->ordered()
                ->get(),
        ]);
    }

    public function create()
    {
        return view('admin.programme.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:120',
            'programme_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'track' => 'nullable|string|max:255',
            'speaker_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $programmeItem = ProgrammeItem::create([
            'title' => $data['title'],
            'category' => $data['category'] ?? null,
            'programme_date' => $data['programme_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'] ?? null,
            'location' => $data['location'] ?? null,
            'track' => $data['track'] ?? null,
            'speaker_name' => $data['speaker_name'] ?? null,
            'description' => $data['description'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('programme.created', 'ProgrammeItem', $programmeItem->id, $programmeItem->toArray());
        }

        return redirect()->route('admin.programme.index')->with('success', 'Programme item created.');
    }

    public function edit(ProgrammeItem $programmeItem)
    {
        return view('admin.programme.edit', compact('programmeItem'));
    }

    public function update(Request $request, ProgrammeItem $programmeItem)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:120',
            'programme_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'track' => 'nullable|string|max:255',
            'speaker_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $before = $programmeItem->toArray();

        $programmeItem->update([
            'title' => $data['title'],
            'category' => $data['category'] ?? null,
            'programme_date' => $data['programme_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'] ?? null,
            'location' => $data['location'] ?? null,
            'track' => $data['track'] ?? null,
            'speaker_name' => $data['speaker_name'] ?? null,
            'description' => $data['description'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log(
                'programme.updated',
                'ProgrammeItem',
                $programmeItem->id,
                ['title' => $programmeItem->title],
                $before,
                $programmeItem->fresh()->toArray()
            );
        }

        return redirect()->route('admin.programme.index')->with('success', 'Programme item updated.');
    }

    public function destroy(ProgrammeItem $programmeItem)
    {
        $before = $programmeItem->toArray();
        $id = $programmeItem->id;
        $programmeItem->delete();

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('programme.deleted', 'ProgrammeItem', $id, $before);
        }

        return redirect()->route('admin.programme.index')->with('success', 'Programme item deleted.');
    }
}
