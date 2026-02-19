<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SettingsController extends Controller
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function index()
    {
        if (! Schema::hasTable('system_settings')) {
            return view('admin.settings.index', ['settings' => collect()])
                ->with('error', 'System settings table not found. Run migrations.');
        }

        $keys = $this->keys();
        $settings = SystemSetting::whereIn('key', $keys)->pluck('value', 'key');

        return view('admin.settings.index', ['settings' => $settings]);
    }

    public function update(Request $request)
    {
        if (! Schema::hasTable('system_settings')) {
            return redirect()->back()->with('error', 'System settings table not found. Run migrations.');
        }

        $data = $request->validate([
            'event_end_at' => 'nullable|date',
            'stream_enabled' => 'required|in:0,1',
            'stream_platform' => 'nullable|string|max:32',
            'stream_primary_url' => 'nullable|url|max:1000',
            'stream_backup_url' => 'nullable|url|max:1000',
            'admin_mfa_required' => 'required|in:0,1',
            'admin_mfa_method' => 'required|in:totp,email_otp',
            'certificate_public_verify_enabled' => 'required|in:0,1',
            'certificate_release_mode' => 'required|in:manual,automatic',
        ]);

        $before = SystemSetting::whereIn('key', array_keys($data))
            ->pluck('value', 'key')
            ->toArray();

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }

        $after = SystemSetting::whereIn('key', array_keys($data))
            ->pluck('value', 'key')
            ->toArray();

        if (Schema::hasTable('audit_logs')) {
            $this->audit->logSettings(
                'updated',
                0,
                ['updated_keys' => array_keys($data)],
                $before,
                $after
            );
        }

        return redirect()->route('admin.settings.index')->with('success', 'System settings updated.');
    }

    protected function keys(): array
    {
        return [
            'event_end_at',
            'stream_enabled',
            'stream_platform',
            'stream_primary_url',
            'stream_backup_url',
            'admin_mfa_required',
            'admin_mfa_method',
            'certificate_public_verify_enabled',
            'certificate_release_mode',
        ];
    }
}
