<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TechPageController extends Controller
{
    public function __invoke(Request $request)
    {
        $info = $this->collectInfo();

        return view('kb.tech', compact('info'));
    }

    private function collectInfo(): array
    {
        return [
            'server_time'    => now()->format('Y-m-d H:i:s T'),
            'app_version'    => config('app.version', '—'),
            'app_env'        => config('app.env'),
            'php_version'    => PHP_VERSION,
            'laravel_version'=> app()->version(),
            'spatie_version' => $this->spatieVersion(),
            'git_commit'     => $this->gitCommit(),
            'git_date'       => $this->gitDate(),
        ];
    }

    private function spatieVersion(): string
    {
        $installed = base_path('vendor/composer/installed.json');
        if (! file_exists($installed)) {
            return '—';
        }

        try {
            $data = json_decode(file_get_contents($installed), true, 512, JSON_THROW_ON_ERROR);
            $packages = $data['packages'] ?? $data;

            foreach ((array) $packages as $package) {
                if (($package['name'] ?? '') === 'spatie/laravel-permission') {
                    return ltrim($package['version'] ?? '—', 'v');
                }
            }
        } catch (\Throwable) {
            // ignore
        }

        return '—';
    }

    private function gitCommit(): string
    {
        $headFile = base_path('.git/HEAD');
        if (! file_exists($headFile)) {
            return '—';
        }

        try {
            $head = trim(file_get_contents($headFile));

            if (str_starts_with($head, 'ref: ')) {
                $ref = substr($head, 5);
                $refFile = base_path('.git/' . $ref);
                if (file_exists($refFile)) {
                    return substr(trim(file_get_contents($refFile)), 0, 7);
                }
            }

            return substr($head, 0, 7);
        } catch (\Throwable) {
            return '—';
        }
    }

    private function gitDate(): string
    {
        $headFile = base_path('.git/HEAD');
        if (! file_exists($headFile)) {
            return '—';
        }

        try {
            $logsHead = base_path('.git/logs/HEAD');
            if (! file_exists($logsHead)) {
                return '—';
            }

            $lines = file($logsHead, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (empty($lines)) {
                return '—';
            }

            $last = end($lines);
            // format: <old-sha> <new-sha> Author Name <email> <unix-timestamp> <tz> <message>
            if (preg_match('/\s(\d{10})\s[+-]\d{4}\s/', $last, $m)) {
                return date('Y-m-d H:i:s', (int) $m[1]);
            }
        } catch (\Throwable) {
            // ignore
        }

        return '—';
    }
}
