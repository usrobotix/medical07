<?php
namespace App\Http\Controllers\Admin;

use App\Console\Commands\BackupPruneCommand;
use App\Http\Controllers\Controller;
use App\Models\AuditEvent;
use App\Models\Backup;
use App\Services\YandexDiskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::with('user')->orderBy('created_at', 'desc')->paginate(20);
        $yandexConfigured = (new YandexDiskService())->isConfigured();
        return view('admin.technical.backups.index', compact('backups', 'yandexConfigured'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'    => 'required|in:db,files,full',
            'preset'  => 'nullable|in:project,storage_app',
            'formats' => 'required|array|min:1',
            'formats.*' => 'in:zip,tar_gz',
            'upload_yandex' => 'nullable|boolean',
        ]);

        $formats = implode(',', $data['formats']);
        $preset  = $data['preset'] ?? 'project';
        $yandex  = $request->boolean('upload_yandex', true) ? 1 : 0;

        Artisan::call('backup:run', [
            '--type'         => $data['type'],
            '--preset'       => $preset,
            '--formats'      => $formats,
            '--yandex'       => $yandex,
            '--initiated-by' => 'user',
            '--user-id'      => auth()->id(),
        ]);

        return redirect()->route('admin.technical.backups.index')
            ->with('success', 'Резервная копия создаётся. Обновите страницу через несколько секунд.');
    }

    public function download(Request $request, Backup $backup)
    {
        $fmt = $request->query('fmt', 'zip');
        $paths = $backup->local_paths ?? [];
        $path = $paths[$fmt] ?? $paths[array_key_first($paths)] ?? null;

        if (!$path || !is_file($path)) {
            abort(404, 'Файл не найден');
        }

        AuditEvent::log('backup.downloaded', ['backup_id' => $backup->id, 'fmt' => $fmt], 'backup', $backup->id);
        return response()->download($path);
    }

    public function destroy(Backup $backup)
    {
        $yandex = new YandexDiskService();
        $pruner = new BackupPruneCommand();
        $pruner->deleteBackupFiles($backup, $yandex);
        AuditEvent::log('backup.deleted', ['backup_id' => $backup->id], 'backup', $backup->id);
        $backup->delete();

        return redirect()->route('admin.technical.backups.index')
            ->with('success', 'Резервная копия удалена.');
    }

    public function testYandex()
    {
        $result = (new YandexDiskService())->testConnection();
        return response()->json($result);
    }
}
