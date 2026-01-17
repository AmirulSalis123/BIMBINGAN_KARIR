<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        // Ambil event beserta relasinya (tiket, kategori, user/penyelenggara)
        $event->load(['tikets', 'kategori', 'user']);

        return view('events.show', compact('event'));
    }
}