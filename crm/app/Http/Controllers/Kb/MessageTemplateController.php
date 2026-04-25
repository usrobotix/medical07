<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\MessageTemplate;
use Illuminate\Http\Request;

class MessageTemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = MessageTemplate::query();

        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        if ($request->filled('target_partner_type')) {
            $query->where('target_partner_type', $request->target_partner_type);
        }

        $templates = $query->orderBy('channel')->orderBy('language')->orderBy('title')->paginate(20)->withQueryString();

        return view('kb.message-templates.index', compact('templates'));
    }

    public function show(MessageTemplate $messageTemplate)
    {
        return view('kb.message-templates.show', compact('messageTemplate'));
    }
}
