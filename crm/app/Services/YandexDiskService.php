<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YandexDiskService
{
    protected string $token;
    protected string $folder;
    protected string $baseUrl = 'https://cloud-api.yandex.net/v1/disk';

    public function __construct()
    {
        $this->token = config('services.yandex_disk.token', '');
        $this->folder = config('services.yandex_disk.folder', 'backups');

        // Normalize folder to app-folder (disk.app_folder scope)
        // Allows .env like: YANDEX_DISK_FOLDER=backups or backups/prod
        if (!str_starts_with($this->folder, 'app:/')) {
            $this->folder = 'app:/' . ltrim($this->folder, '/');
        }
    }

    protected function headers(): array
    {
        return ['Authorization' => 'OAuth ' . $this->token];
    }

    public function isConfigured(): bool
    {
        return !empty($this->token);
    }

    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return ['ok' => false, 'message' => 'Токен Яндекс.Диска не настроен'];
        }

        try {
            // More meaningful than just GET /v1/disk: check app folder access
            $resp = Http::withHeaders($this->headers())->get($this->baseUrl . '/resources', [
                'path' => 'app:/',
            ]);

            if ($resp->successful()) {
                // Also ensure target folder exists
                $this->ensureFolder($this->folder);
                return ['ok' => true, 'message' => 'Соединение успешно'];
            }

            Log::error('Yandex Disk testConnection failed', [
                'status' => $resp->status(),
                'body' => $resp->body(),
            ]);

            return ['ok' => false, 'message' => 'Ошибка: ' . $resp->status()];
        } catch (\Exception $e) {
            Log::error('Yandex Disk testConnection exception: ' . $e->getMessage());
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    public function ensureFolder(string $path): void
    {
        $path = trim($path);

        // supports app:/backups/sub
        if (str_starts_with($path, 'app:/')) {
            $sub = trim(substr($path, 5), '/'); // after app:/
            if ($sub === '') return;

            $parts = explode('/', $sub);
            $current = 'app:/';

            foreach ($parts as $part) {
                $current = rtrim($current, '/') . '/' . $part;

                $resp = Http::withHeaders($this->headers())
                    ->send('PUT', $this->baseUrl . '/resources?path=' . urlencode($current));

                // 201/409 are fine (created / already exists). Log other failures.
                if (!$resp->successful() && $resp->status() !== 409) {
                    Log::error('Yandex Disk ensureFolder failed', [
                        'status' => $resp->status(),
                        'body' => $resp->body(),
                        'path' => $current,
                    ]);
                }
            }

            return;
        }

        // Fallback legacy behavior (non-app paths)
        $parts = explode('/', trim($path, '/'));
        $current = '';

        foreach ($parts as $part) {
            $current .= '/' . $part;

            $resp = Http::withHeaders($this->headers())
                ->send('PUT', $this->baseUrl . '/resources?path=' . urlencode($current));

            if (!$resp->successful() && $resp->status() !== 409) {
                Log::error('Yandex Disk ensureFolder (legacy) failed', [
                    'status' => $resp->status(),
                    'body' => $resp->body(),
                    'path' => $current,
                ]);
            }
        }
    }

    public function upload(string $localPath, string $remoteName): ?string
    {
        if (!$this->isConfigured()) return null;

        try {
            $remotePath = rtrim($this->folder, '/') . '/' . ltrim($remoteName, '/');

            $this->ensureFolder($this->folder);

            // 1) get upload URL
            $resp = Http::withHeaders($this->headers())
                ->get($this->baseUrl . '/resources/upload', [
                    'path' => $remotePath,
                    'overwrite' => 'true',
                ]);

            if (!$resp->successful()) {
                Log::error('Yandex Disk get upload URL failed', [
                    'status' => $resp->status(),
                    'body' => $resp->body(),
                    'remotePath' => $remotePath,
                ]);
                return null;
            }

            $uploadUrl = $resp->json('href');
            if (empty($uploadUrl)) {
                Log::error('Yandex Disk upload URL missing in response', [
                    'remotePath' => $remotePath,
                    'body' => $resp->json(),
                ]);
                return null;
            }

            // 2) upload file bytes
            $fileContent = file_get_contents($localPath);

            $uploadResp = Http::withHeaders($this->headers())
                ->withBody($fileContent, 'application/octet-stream')
                ->put($uploadUrl);

            if (in_array($uploadResp->status(), [200, 201], true)) {
                return $remotePath;
            }

            Log::error('Yandex Disk upload PUT failed', [
                'status' => $uploadResp->status(),
                'body' => $uploadResp->body(),
                'remotePath' => $remotePath,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Yandex Disk upload failed: ' . $e->getMessage());
            return null;
        }
    }

    public function download(string $remotePath): ?string
    {
        if (!$this->isConfigured()) return null;

        try {
            $resp = Http::withHeaders($this->headers())
                ->get($this->baseUrl . '/resources/download', [
                    'path' => $remotePath,
                ]);

            if (!$resp->successful()) {
                Log::error('Yandex Disk download URL failed', [
                    'status' => $resp->status(),
                    'body'   => $resp->body(),
                    'path'   => $remotePath,
                ]);
                return null;
            }

            $downloadUrl = $resp->json('href');
            if (empty($downloadUrl)) {
                Log::error('Yandex Disk download href missing', ['path' => $remotePath]);
                return null;
            }

            $tmpPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'yadisk_' . uniqid() . '_' . basename($remotePath);

            $fileResp = Http::timeout(600)->sink($tmpPath)->get($downloadUrl);
            if (!$fileResp->successful()) {
                Log::error('Yandex Disk file GET failed', ['status' => $fileResp->status()]);
                return null;
            }

            return $tmpPath;
        } catch (\Exception $e) {
            Log::error('Yandex Disk download exception: ' . $e->getMessage());
            return null;
        }
    }

    public function delete(string $remotePath): bool
    {
        if (!$this->isConfigured()) return false;

        try {
            $resp = Http::withHeaders($this->headers())
                ->delete($this->baseUrl . '/resources', [
                    'path' => $remotePath,
                    'permanently' => 'true',
                ]);

            return $resp->successful() || $resp->status() === 204;
        } catch (\Exception $e) {
            Log::error('Yandex Disk delete failed: ' . $e->getMessage());
            return false;
        }
    }
}