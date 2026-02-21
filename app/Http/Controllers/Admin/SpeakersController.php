<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Speaker;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SpeakersController extends Controller
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function index()
    {
        if (! Schema::hasTable('speakers')) {
            return view('admin.speakers.index', ['speakers' => collect()])
                ->with('error', 'Speakers table not found. Run migrations.');
        }

        return view('admin.speakers.index', [
            'speakers' => Speaker::query()
                ->ordered()
                ->get(),
        ]);
    }

    public function create()
    {
        return view('admin.speakers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:5000',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'photo_url' => 'nullable|string|max:1000',
            'photo_file' => 'nullable|image|max:5120',
            'website_url' => 'nullable|url|max:1000',
            'twitter_url' => 'nullable|url|max:1000',
            'linkedin_url' => 'nullable|url|max:1000',
            'expertise_topics' => 'nullable|string',
            'session_title' => 'nullable|string|max:500',
            'session_description' => 'nullable|string|max:5000',
            'session_time' => 'nullable|date_format:Y-m-d H:i',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
            'is_keynote' => 'nullable|boolean',
        ]);

        $photoUrl = $data['photo_url'] ?? null;
        if ($request->hasFile('photo_file')) {
            $path = $request->file('photo_file')->store('speakers', 'public');
            $photoUrl = Storage::disk('public')->url($path);
        }

        $topics = ! blank($data['expertise_topics']) 
            ? array_map('trim', explode(',', $data['expertise_topics']))
            : null;

        $speaker = Speaker::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'title' => $data['title'] ?? null,
            'organization' => $data['organization'] ?? null,
            'bio' => $data['bio'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'photo_url' => $photoUrl,
            'website_url' => $data['website_url'] ?? null,
            'twitter_url' => $data['twitter_url'] ?? null,
            'linkedin_url' => $data['linkedin_url'] ?? null,
            'expertise_topics' => $topics,
            'session_title' => $data['session_title'] ?? null,
            'session_description' => $data['session_description'] ?? null,
            'session_time' => $data['session_time'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
            'is_keynote' => $request->boolean('is_keynote', false),
        ]);

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('speaker.created', 'Speaker', $speaker->id, $speaker->toArray());
        }

        return redirect()->route('admin.speakers.index')->with('success', 'Speaker created successfully.');
    }

    public function edit(Speaker $speaker)
    {
        return view('admin.speakers.edit', compact('speaker'));
    }

    public function update(Request $request, Speaker $speaker)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:5000',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'photo_url' => 'nullable|string|max:1000',
            'photo_file' => 'nullable|image|max:5120',
            'website_url' => 'nullable|url|max:1000',
            'twitter_url' => 'nullable|url|max:1000',
            'linkedin_url' => 'nullable|url|max:1000',
            'expertise_topics' => 'nullable|string',
            'session_title' => 'nullable|string|max:500',
            'session_description' => 'nullable|string|max:5000',
            'session_time' => 'nullable|date_format:Y-m-d H:i',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
            'is_keynote' => 'nullable|boolean',
        ]);

        $before = $speaker->toArray();

        $photoUrl = $data['photo_url'] ?? $speaker->photo_url;
        if ($request->hasFile('photo_file')) {
            $path = $request->file('photo_file')->store('speakers', 'public');
            $photoUrl = Storage::disk('public')->url($path);
        }

        $topics = ! blank($data['expertise_topics'])
            ? array_map('trim', explode(',', $data['expertise_topics']))
            : null;

        $speaker->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'title' => $data['title'] ?? null,
            'organization' => $data['organization'] ?? null,
            'bio' => $data['bio'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'photo_url' => $photoUrl,
            'website_url' => $data['website_url'] ?? null,
            'twitter_url' => $data['twitter_url'] ?? null,
            'linkedin_url' => $data['linkedin_url'] ?? null,
            'expertise_topics' => $topics,
            'session_title' => $data['session_title'] ?? null,
            'session_description' => $data['session_description'] ?? null,
            'session_time' => $data['session_time'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
            'is_keynote' => $request->boolean('is_keynote'),
        ]);

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log(
                'speaker.updated',
                'Speaker',
                $speaker->id,
                ['name' => $speaker->full_name],
                $before,
                $speaker->fresh()->toArray()
            );
        }

        return redirect()->route('admin.speakers.index')->with('success', 'Speaker updated successfully.');
    }

    public function destroy(Speaker $speaker)
    {
        $name = $speaker->full_name;
        $speaker->delete();

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('speaker.deleted', 'Speaker', $speaker->id, ['name' => $name]);
        }

        return redirect()->route('admin.speakers.index')->with('success', 'Speaker deleted successfully.');
    }

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'speakers' => 'required|array|min:1',
            'speakers.*' => 'integer|exists:speakers,id',
        ]);

        $speakers = Speaker::whereIn('id', $data['speakers'])->get();

        match ($data['action']) {
            'activate' => $speakers->each->update(['is_active' => true]),
            'deactivate' => $speakers->each->update(['is_active' => false]),
            'delete' => $speakers->each(fn ($s) => $s->delete()),
        };

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log(
                "speaker.bulk_{$data['action']}",
                'Speaker',
                0,
                ['count' => count($data['speakers']), 'action' => $data['action']]
            );
        }

        return redirect()->route('admin.speakers.index')
            ->with('success', ucfirst($data['action']) . ' successful for ' . count($speakers) . ' speaker(s).');
    }
}
