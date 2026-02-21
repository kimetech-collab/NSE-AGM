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
        ]);

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate([
                'key' => $key,
            ], [
                'value' => ($value === null || $value === '') ? null : (string) $value,
            ]);
        }

        return redirect()->route('admin.stream.index')->with('success', 'Stream settings updated.');
    }
}
