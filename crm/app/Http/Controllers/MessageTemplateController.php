<?php

namespace App\Http\Controllers;

use App\Models\MessageTemplate;

class MessageTemplateController extends Controller
{
    public function index()
    {
        $templates = MessageTemplate::orderBy('title')->paginate(20);

        return view('message-templates.index', compact('templates'));
    }
}
