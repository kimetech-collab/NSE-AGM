<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class StreamController extends Controller
{
    public function index()
    {
        $settings = collect();
        if (Schema::hasTable('system_settings')) {
            $settings = SystemSetting::whereIn('key', [
                'stream_enabled',
                'stream_platform',
                'stream_primary_url',
                'stream_backup_url',
                'event_end_at',
            ])->pluck('value', 'key');
        }

        return view('admin.stream.index', ['settings' => $settings]);
    }

    public function update(Request $request)
    {
        if (! Schema::hasTable('system_settings')) {
            return redirect()->back()->with('error', 'System settings table not found. Run migrations.');
        }

        $data = $request->validate([
            'stream_enabled' => 'nullable|in:0,1',
            'stream_platform' => 'nullable|string|max:32',
            'stream_primary_url' => 'nullable|url|max:1000',
            'stream_backup_url' => 'nullable|url|max:1000',
            'event_end_at' => 'nullable|date',
        ]);

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }

        return redirect()->route('admin.stream.index')->with('success', 'Stream settings updated.');
    }
}
