<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditEvent;
use App\Models\User;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditEvent::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->entity_type);
        }

        $events = $query->paginate(50)->withQueryString();
        $users = User::orderBy('name')->get();
        $actions = AuditEvent::distinct()->orderBy('action')->pluck('action');
        $entityTypes = AuditEvent::whereNotNull('entity_type')->distinct()->orderBy('entity_type')->pluck('entity_type');

        return view('admin.technical.audit.index', compact('events', 'users', 'actions', 'entityTypes'));
    }
}
