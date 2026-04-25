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

    public function create()
    {
        return view('kb.message-templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'               => 'required|string|max:255',
            'channel'             => 'required|in:email,whatsapp,telegram',
            'language'            => 'required|in:ru,en',
            'subject'             => 'nullable|string|max:500',
            'body'                => 'required|string',
            'target_partner_type' => 'nullable|in:clinic,translator,curator',
        ]);

        $template = MessageTemplate::create($data);

        return redirect()->route('kb.message-templates.show', $template)->with('success', 'Шаблон добавлен.');
    }

    public function edit(MessageTemplate $messageTemplate)
    {
        return view('kb.message-templates.edit', compact('messageTemplate'));
    }

    public function update(Request $request, MessageTemplate $messageTemplate)
    {
        $data = $request->validate([
            'title'               => 'required|string|max:255',
            'channel'             => 'required|in:email,whatsapp,telegram',
            'language'            => 'required|in:ru,en',
            'subject'             => 'nullable|string|max:500',
            'body'                => 'required|string',
            'target_partner_type' => 'nullable|in:clinic,translator,curator',
        ]);

        $messageTemplate->update($data);

        return redirect()->route('kb.message-templates.show', $messageTemplate)->with('success', 'Шаблон обновлён.');
    }

    public function destroy(MessageTemplate $messageTemplate)
    {
        $messageTemplate->delete();

        return redirect()->route('kb.message-templates.index')->with('success', 'Шаблон удалён.');
    }
}
