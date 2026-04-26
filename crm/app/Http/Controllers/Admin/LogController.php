<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditEvent;
use Illuminate\Http\Request;

class LogController extends Controller
{
    private function allowedLogsDir(): string
    {
        return storage_path('logs');
    }

    private function resolveLogPath(string $filename): string
    {
        $filename = basename($filename);
        if (!preg_match('/^[\w\-\.]+\.log$/', $filename)) {
            abort(400, 'Недопустимое имя файла');
        }
        $path = $this->allowedLogsDir() . DIRECTORY_SEPARATOR . $filename;
        if (!str_starts_with(realpath(dirname($path)) ?: '', realpath($this->allowedLogsDir()))) {
            abort(403, 'Доступ запрещён');
        }
        return $path;
    }

    private function getLogFiles(): array
    {
        $dir = $this->allowedLogsDir();
        if (!is_dir($dir)) return [];
        $files = glob($dir . DIRECTORY_SEPARATOR . '*.log') ?: [];
        return array_map('basename', $files);
    }

    public function index(Request $request)
    {
        $files = $this->getLogFiles();
        $selectedFile = $request->input('file', 'laravel.log');
        if (!in_array($selectedFile, $files)) {
            $selectedFile = $files[0] ?? null;
        }

        $lines = [];
        $totalLines = 0;
        if ($selectedFile) {
            $path = $this->resolveLogPath($selectedFile);
            if (is_file($path)) {
                $limit = (int)$request->input('lines', 500);
                $limit = min(max($limit, 10), 5000);
                $level = $request->input('level', '');
                $search = $request->input('search', '');
                $allLines = $this->readLastLines($path, 10000);
                $totalLines = count($allLines);
                if ($level || $search) {
                    $allLines = array_filter($allLines, function ($line) use ($level, $search) {
                        $matchLevel = !$level || stripos($line, '.' . strtoupper($level) . ':') !== false
                            || stripos($line, '[' . strtoupper($level) . ']') !== false;
                        $matchSearch = !$search || stripos($line, $search) !== false;
                        return $matchLevel && $matchSearch;
                    });
                }
                $lines = array_slice(array_values($allLines), -$limit);
            }
        }

        return view('admin.technical.logs.index', compact('files', 'selectedFile', 'lines', 'totalLines'));
    }

    public function download(Request $request)
    {
        $filename = $request->input('file', 'laravel.log');
        $path = $this->resolveLogPath($filename);
        if (!is_file($path)) abort(404);
        AuditEvent::log('log.downloaded', ['file' => $filename]);
        return response()->download($path);
    }

    public function clear(Request $request)
    {
        $filename = $request->input('file', 'laravel.log');
        $path = $this->resolveLogPath($filename);
        if (!is_file($path)) abort(404);
        file_put_contents($path, '');
        AuditEvent::log('log.cleared', ['file' => $filename]);
        return redirect()->route('admin.technical.logs.index', ['file' => $filename])
            ->with('success', "Лог {$filename} очищен.");
    }

    private function readLastLines(string $path, int $n): array
    {
        $lines = [];
        $fp = @fopen($path, 'r');
        if (!$fp) return $lines;
        $size = filesize($path);
        if (!$size) { fclose($fp); return $lines; }
        $content = fread($fp, $size);
        fclose($fp);
        if (!$content) return $lines;
        $all = explode("\n", $content);
        return array_slice($all, -$n);
    }
}
