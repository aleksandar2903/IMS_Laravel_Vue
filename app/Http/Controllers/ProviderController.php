<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Http\Requests\ProviderRequest;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * Display a listing of the Provs
     *
     * @param  \App\Provider  $model
     * @return \Illuminate\View\View
     */
    public function index(Provider $model)
    {
        $providers = Provider::latest()->get();

        return view('providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new Prov
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('providers.create');
    }

    /**
     * Store a newly created Provider in storage
     *
     * @param  \App\Http\Requests\ProviderRequest  $request
     * @param  \App\Provider  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Provider $provider)
    {
        $request->validate(['name'=>'required|min:3']);
        $provider->create($request->all());

        return redirect()
            ->route('providers.index')
            ->withStatus('Supplier successfully registered.');
    }

    /**
     * Show the form for editing the specified Provider
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\View\View
     */
    public function edit(Provider $provider)
    {
        return view('providers.edit', compact('provider'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Provider $provider)
    {
        $transactions = $provider->transactions()->latest()->limit(25)->get();

        $receipts = $provider->receipts()->latest()->limit(25)->get();

        return view('providers.show', compact('provider', 'transactions', 'receipts'));
    }

    /**
     * Update the specified Provider in storage
     *
     * @param  \App\Http\Requests\ProviderRequest  $request
     * @param  \App\Provider  $Provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Provider $provider)
    {
        $provider->update($request->all());

        return redirect()
            ->route('providers.index')
            ->withStatus('Supplier updated successfully.');
    }

    /**
     * Remove the specified Provider from storage
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Provider $provider)
    {
        $provider->delete();

        return redirect()
            ->route('providers.index')
            ->withStatus('Supplier removed successfully.');
    }
}
