<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Store a new contact message.
     */
    public function store(StoreContactRequest $request): RedirectResponse
    {
        Contact::query()->create($request->validated());

        return back()->with('success', 'Pesan berhasil dikirim. Kami akan mengarahkan Anda ke WhatsApp.');
    }
}
