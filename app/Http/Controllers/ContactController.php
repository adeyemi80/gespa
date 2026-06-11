<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreContactRequest;
 use App\Models\User;
use App\Notifications\NouveauMessageContact;


class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        return view('contacts.index', [
            'contacts' => Contact::latest()->paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
  

public function store(StoreContactRequest $request): RedirectResponse
{
    $contact = Contact::create($request->all());

    // récupérer admin + directeur
    $users = User::whereIn('role', ['admin'/** , 'directeur'*/])->get();

    foreach ($users as $user) {
        $user->notify(new NouveauMessageContact($contact));
    }

    return redirect()->route('dashboard')
        ->withSuccess('Votre Message a été envoyé avec Succès!');
}

    /**
     * Display the specified resource.
     */
    public function show($contact_id) : View
    {
    
        $contact = Contact::find($contact_id);
    return view('contacts.show', [
        'contact' => $contact
    ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit() : View
   {
    return view('contacts.present');
    }

   
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnneeRequest $request, $annee_id) : RedirectResponse
    {
       //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($contact_id) : RedirectResponse
    {
        $contact = Contact::find($contact_id);
        $contact->delete($contact_id);
        return back()
                ->withSuccess('Contact is deleted successfully.');
    }

}
