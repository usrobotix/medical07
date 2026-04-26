<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupSetting;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $settings = BackupSetting::instance();
        return view('admin.technical.schedule.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'enabled'       => 'nullable|boolean',
            'schedule_time' => ['required', 'regex:/^\d{2}:\d{2}$/'],
            'backup_type'   => 'required|in:db,files,full',
            'file_preset'   => 'nullable|in:project,storage_app',
            'formats'       => 'required|array|min:1',
            'formats.*'     => 'in:zip,tar_gz',
            'upload_yandex' => 'nullable|boolean',
        ]);

        $settings = BackupSetting::instance();
        $settings->update([
            'enabled'       => $request->boolean('enabled'),
            'schedule_time' => $data['schedule_time'],
            'backup_type'   => $data['backup_type'],
            'file_preset'   => $data['file_preset'] ?? 'project',
            'formats'       => $data['formats'],
            'upload_yandex' => $request->boolean('upload_yandex'),
        ]);

        return redirect()->route('admin.technical.schedule.index')
            ->with('success', 'Настройки расписания сохранены.');
    }
}
