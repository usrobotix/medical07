<?php
namespace App\Console\Commands;

use App\Models\AuditEvent;
use Illuminate\Console\Command;

class AuditPruneCommand extends Command
{
    protected $signature = 'audit:prune {--days=360}';
    protected $description = 'Prune audit events older than N days';

    public function handle(): int
    {
        $days = (int)$this->option('days');
        $deleted = AuditEvent::where('created_at', '<', now()->subDays($days))->delete();
        $this->info("Pruned {$deleted} audit events older than {$days} days.");
        return 0;
    }
}
