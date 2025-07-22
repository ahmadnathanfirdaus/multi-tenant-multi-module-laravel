<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CrmContact;
use Illuminate\Support\Facades\Auth;

class CrmController extends Controller
{
    public function index()
    {
        $contacts = CrmContact::with('user')->latest()->paginate(10);
        return view('modules.crm.index', compact('contacts'));
    }

    public function show(CrmContact $contact)
    {
        return view('modules.crm.show', compact('contact'));
    }

    public function create()
    {
        return view('modules.crm.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:20',
            'company' => 'nullable|max:255',
            'position' => 'nullable|max:255',
            'address' => 'nullable',
            'status' => 'required|in:lead,prospect,customer,inactive',
            'deal_value' => 'nullable|numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();

        CrmContact::create($validated);

        return redirect()->route('modules.crm.index')
                        ->with('success', 'Contact created successfully!');
    }

    public function edit(CrmContact $contact)
    {
        return view('modules.crm.edit', compact('contact'));
    }

    public function update(Request $request, CrmContact $contact)
    {
        $validated = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:20',
            'company' => 'nullable|max:255',
            'position' => 'nullable|max:255',
            'address' => 'nullable',
            'status' => 'required|in:lead,prospect,customer,inactive',
            'deal_value' => 'nullable|numeric|min:0',
        ]);

        $contact->update($validated);

        return redirect()->route('modules.crm.index')
                        ->with('success', 'Contact updated successfully!');
    }

    public function destroy(CrmContact $contact)
    {
        $contact->delete();

        return redirect()->route('modules.crm.index')
                        ->with('success', 'Contact deleted successfully!');
    }
}
