<?php

namespace App\Http\Controllers\Kb;

use App\Http\Controllers\Controller;
use App\Models\MessageTemplate;

class MessageTemplateController extends Controller
{
    public function index()
    {
        $templates = MessageTemplate::orderBy('channel')->orderBy('title')->paginate(20);

        return view('kb.message-templates.index', compact('templates'));
    }

    public function show(MessageTemplate $messageTemplate)
    {
        return view('kb.message-templates.show', compact('messageTemplate'));
    }
}
