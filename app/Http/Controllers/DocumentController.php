<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::query()
            ->where('user_id', auth()->id());

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $documents = $query->latest()->paginate(10);
        $categories = Document::distinct('category')->pluck('category');

        // Add file type helpers for each document
        $documents->each(function ($document) {
            $document->file_type = strtolower(pathinfo($document->file_name, PATHINFO_EXTENSION));
        });

        return view('vet.documents.index', [
            'documents' => $documents,
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'is_private' => 'boolean'
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'category' => $request->category,
            'user_id' => auth()->id(),
            'is_private' => $request->is_private ?? true
        ]);

        return redirect()->route('vet.documents.index')
            ->with('success', 'Document uploaded successfully.');
    }

    public function destroy(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('vet.documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    public function download(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        return Storage::disk('public')->download(
            $document->file_path, 
            $document->file_name
        );
    }
} 