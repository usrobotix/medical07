<?php
namespace App\Http\Controllers\Admin;

use App\Console\Commands\BackupPruneCommand;
use App\Http\Controllers\Controller;
use App\Jobs\RestoreDatabaseFromBackupJob;
use App\Jobs\RunBackupJob;
use App\Models\AuditEvent;
use App\Models\Backup;
use App\Services\YandexDiskService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::with('user')->orderBy('created_at', 'desc')->paginate(20);
        $yandexConfigured = (new YandexDiskService())->isConfigured();
        $queueIsSync = config('queue.default') === 'sync';
        return view('admin.technical.backups.index', compact('backups', 'yandexConfigured', 'queueIsSync'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'          => 'required|in:db,files,full',
            'preset'        => 'nullable|in:project,storage_app',
            'formats'       => 'required|array|min:1',
            'formats.*'     => 'in:zip,tar_gz',
            'upload_yandex' => 'nullable|boolean',
        ]);

        $preset        = $data['preset'] ?? 'project';
        $uploadYandex  = $request->boolean('upload_yandex', true);

        $backup = Backup::create([
            'type'         => $data['type'],
            'kind'         => 'backup',
            'file_preset'  => in_array($data['type'], ['files', 'full']) ? $preset : null,
            'formats'      => $data['formats'],
            'local_paths'  => null,
            'remote_paths' => null,
            'size_bytes'   => 0,
            'status'       => 'queued',
            'initiated_by' => 'user',
            'user_id'      => auth()->id(),
            'progress_percent' => 0,
            'current_step'     => 'queued',
        ]);

        AuditEvent::log(
            'backup.queued',
            [
                'type'         => $data['type'],
                'preset'       => $preset,
                'formats'      => $data['formats'],
                'initiated_by' => 'user',
            ],
            'backup',
            $backup->id
        );

        RunBackupJob::dispatch($backup->id, $uploadYandex);

        return redirect()->route('admin.technical.backups.index')
            ->with('success', 'Резервная копия поставлена в очередь. Статус обновится автоматически.');
    }

    public function status(Backup $backup)
    {
        return response()->json([
            'id'               => $backup->id,
            'status'           => $backup->status,
            'progress_percent' => $backup->progress_percent,
            'current_step'     => $backup->current_step,
            'error_message'    => $backup->error_message,
            'finished_at'      => $backup->finished_at?->toIso8601String(),
        ]);
    }

    public function download(Request $request, Backup $backup)
    {
        $fmt   = $request->query('fmt', 'zip');
        $paths = $backup->local_paths ?? [];
        $path  = $paths[$fmt] ?? $paths[array_key_first($paths)] ?? null;

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

    /**
     * Start a DB restore from an existing backup record.
     * Creates a restore tracking record and dispatches the async job.
     */
    public function restore(Request $request, Backup $backup)
    {
        // Only allow restoring done DB/full backups that are original backup records (not restore records)
        if (
            $backup->status !== 'done'
            || !in_array($backup->type, ['db', 'full'])
            || ($backup->kind ?? 'backup') !== 'backup'
        ) {
            return redirect()->route('admin.technical.backups.index')
                ->with('error', 'Восстановление возможно только для успешно завершённых резервных копий БД.');
        }

        if ($request->input('confirmed') !== '1') {
            return redirect()->route('admin.technical.backups.index')
                ->with('error', 'Необходимо подтвердить восстановление.');
        }

        $restoreRecord = Backup::create([
            'type'             => 'db',
            'kind'             => 'restore',
            'formats'          => ['zip'],
            'status'           => 'queued',
            'initiated_by'     => 'user',
            'user_id'          => auth()->id(),
            'progress_percent' => 0,
            'current_step'     => 'queued',
            'size_bytes'       => 0,
        ]);

        AuditEvent::log(
            'backup.restore_queued',
            [
                'source_backup_id'  => $backup->id,
                'restore_record_id' => $restoreRecord->id,
            ],
            'backup',
            $backup->id
        );

        RestoreDatabaseFromBackupJob::dispatch($backup->id, $restoreRecord->id);

        return redirect()->route('admin.technical.backups.index')
            ->with('success', 'Восстановление БД поставлено в очередь. Следите за прогрессом в таблице ниже.');
    }
}

