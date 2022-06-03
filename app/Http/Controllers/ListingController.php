<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;

class ListingController extends Controller
{
    public function showAll(): View|Factory 
    {
        return view('listings.index', ['listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)]);
    }

    public function showSingle(Listing $listing): View|Factory 
    {
        return view('listings.show', ['listing' => $listing]);
    }

    public function create(): View|Factory 
    {
        return view('listings.create');
    }

    public function save(Request $request): Redirector|RedirectResponse 
    {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);
        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $formFields['user_id'] = auth()->id();
        Listing::create($formFields);
        return redirect('/')->with('message', 'Listing created successfully!');
    }

    public function edit(Listing $listing): View|Factory 
    {
        return view('listings.edit', ['listing' => $listing]);
    }

    public function update(Request $request, Listing $listing): RedirectResponse 
    {
        $this->checkAuthorization($listing->user_id);
        $formFields = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);
        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $listing->update($formFields);
        return back()->with('message', 'Listing updated successfully!');
    }

    public function delete(Listing $listing): Redirector|RedirectResponse 
    {
        $this->checkAuthorization($listing->user_id);
        $listing->delete();
        return redirect('/')->with('message', 'Listing deleted successfully!');
    }

    public function manage(): View|Factory 
    {
        return view('listings.manage', ['listings' => auth()->user()->listings]);
    }

    private function checkAuthorization(int $user_id): void
    {
        if ($user_id != auth()->id()) {
            abort(403, 'Unauthorized action');
        }
    }
}
