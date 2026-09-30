<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['q' => 'nullable|string|max:100', 'status' => ['nullable', Rule::in(array_keys(Inquiry::STATUSES))]]);
        $inquiries = Inquiry::when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['q'] ?? null, fn ($q, $term) => $q->where(fn ($q) => $q->where('name', 'like', '%'.$term.'%')->orWhere('company', 'like', '%'.$term.'%')->orWhere('email', 'like', '%'.$term.'%')))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(Inquiry $inquiry)
    {
        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function update(Request $request, Inquiry $inquiry)
    {
        $data = $request->validate(['status' => ['required', Rule::in(array_keys(Inquiry::STATUSES))], 'notes' => 'nullable|string|max:10000']);
        $inquiry->update([...$data, 'updated_by' => $request->user()->id]);

        return back()->with('status', 'Status inquiry berhasil diperbarui.');
    }

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')->with('status', 'Inquiry berhasil dihapus.');
    }
}
