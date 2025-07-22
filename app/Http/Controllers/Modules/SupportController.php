<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['user', 'assignedAgent'])->latest()->paginate(10);
        return view('modules.support.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        return view('modules.support.show', compact('ticket'));
    }

    public function create()
    {
        return view('modules.support.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|max:255',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'nullable|max:255',
        ]);

        $validated['user_id'] = Auth::id();

        SupportTicket::create($validated);

        return redirect()->route('modules.support.index')
                        ->with('success', 'Support ticket created successfully!');
    }

    public function edit(SupportTicket $ticket)
    {
        return view('modules.support.edit', compact('ticket'));
    }

    public function update(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'subject' => 'required|max:255',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'category' => 'nullable|max:255',
        ]);

        if ($validated['status'] === 'resolved' && !$ticket->resolved_at) {
            $validated['resolved_at'] = now();
        }

        $ticket->update($validated);

        return redirect()->route('modules.support.index')
                        ->with('success', 'Support ticket updated successfully!');
    }

    public function destroy(SupportTicket $ticket)
    {
        $ticket->delete();

        return redirect()->route('modules.support.index')
                        ->with('success', 'Support ticket deleted successfully!');
    }
}
