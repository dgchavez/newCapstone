<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::latest()->get();
        return view('admin.policies.index', compact('policies'));
    }

    public function show(Policy $policy)
    {
        return view('admin.policies.show', compact('policy'));
    }

    public function create()
    {
        return view('admin.policies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:terms,privacy,cookies',
            'content' => 'required|string',
            'is_published' => 'sometimes|boolean'
        ]);

        // Set version number (increment from latest)
        $latestVersion = Policy::where('type', $validated['type'])->max('version') ?? 0;
        $validated['version'] = $latestVersion + 1;

        // If publishing, unpublish others of same type
        if ($request->boolean('is_published')) {
            Policy::where('type', $validated['type'])->update(['is_published' => false]);
        }

        Policy::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('policies.index')->with('success', 'Policy created successfully');
    }

    public function edit(Policy $policy)
    {
        return view('admin.policies.edit', compact('policy'));
    }

    public function update(Request $request, Policy $policy)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:terms,privacy,cookies',
            'content' => 'required|string',
            'is_published' => 'sometimes|boolean'
        ]);

        // If changing type, reset version
        if ($policy->type !== $validated['type']) {
            $latestVersion = Policy::where('type', $validated['type'])->max('version') ?? 0;
            $validated['version'] = $latestVersion + 1;
        }

        // If publishing, unpublish others of same type
        if ($request->boolean('is_published')) {
            Policy::where('type', $validated['type'])
                ->where('id', '!=', $policy->id)
                ->update(['is_published' => false]);
        }

        $policy->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('policies.index')->with('success', 'Policy updated successfully');
    }

    public function destroy(Policy $policy)
    {
        $policy->delete();
        return redirect()->route('policies.index')->with('success', 'Policy deleted successfully');
    }

    public function togglePublish(Policy $policy)
    {
        if ($policy->is_published) {
            $policy->update(['is_published' => false]);
        } else {
            // Unpublish others of same type first
            Policy::where('type', $policy->type)
                ->where('id', '!=', $policy->id)
                ->update(['is_published' => false]);
            
            $policy->update(['is_published' => true]);
        }

        return back()->with('success', 'Policy publication status updated');
    }
}