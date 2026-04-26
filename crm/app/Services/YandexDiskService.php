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
        $this->folder = config('services.yandex_disk.folder', '/medical07/backups');
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
            $resp = Http::withHeaders($this->headers())->get($this->baseUrl);
            if ($resp->successful()) {
                return ['ok' => true, 'message' => 'Соединение успешно'];
            }
            return ['ok' => false, 'message' => 'Ошибка: ' . $resp->status()];
        } catch (\Exception $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    public function ensureFolder(string $path): void
    {
        $parts = explode('/', trim($path, '/'));
        $current = '';
        foreach ($parts as $part) {
            $current .= '/' . $part;
            Http::withHeaders($this->headers())
                ->put($this->baseUrl . '/resources', ['path' => $current]);
        }
    }

    public function upload(string $localPath, string $remoteName): ?string
    {
        if (!$this->isConfigured()) return null;
        try {
            $remotePath = rtrim($this->folder, '/') . '/' . $remoteName;
            $this->ensureFolder($this->folder);
            $resp = Http::withHeaders($this->headers())
                ->get($this->baseUrl . '/resources/upload', [
                    'path' => $remotePath,
                    'overwrite' => 'true',
                ]);
            if (!$resp->successful()) return null;
            $uploadUrl = $resp->json('href');
            $fileContent = file_get_contents($localPath);
            $uploadResp = Http::withHeaders($this->headers())
                ->withBody($fileContent, 'application/octet-stream')
                ->put($uploadUrl);
            if ($uploadResp->status() === 201 || $uploadResp->status() === 200) {
                return $remotePath;
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Yandex Disk upload failed: ' . $e->getMessage());
            return null;
        }
    }

    public function delete(string $remotePath): bool
    {
        if (!$this->isConfigured()) return false;
        try {
            $resp = Http::withHeaders($this->headers())
                ->delete($this->baseUrl . '/resources', ['path' => $remotePath, 'permanently' => 'true']);
            return $resp->successful() || $resp->status() === 204;
        } catch (\Exception $e) {
            return false;
        }
    }
}
