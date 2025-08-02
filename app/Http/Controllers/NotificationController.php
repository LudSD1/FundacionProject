<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notificaciones = auth()->user()->notifications()->latest()->paginate(20);
        return view('notificaciones.index', compact('notificaciones'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notificación marcada como leída');
    }

    public function markAllAsRead(Request $request)
    {
        auth()->user()->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }

    public function delete($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notificación eliminada correctamente');
    }

    public function deleteAllRead()
    {
        auth()->user()->notifications()
            ->whereNotNull('read_at')
            ->delete();

        return back()->with('success', 'Todas las notificaciones leídas han sido eliminadas');
    }
}
