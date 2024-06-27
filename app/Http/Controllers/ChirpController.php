<?php

namespace App\Http\Controllers;
use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return response('Hello, World!');
        // return view('chirps.index');
        // echo "<pre>";
        // print_r(Chirp::with('user')->latest()->get()->toArray());
        return view('chirps.index', [
            'chirps' => Chirp::where('user_id',Auth::user()->id)->with('user')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request):RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ] );
    // echo "<pre>";
    //     print_r($request->user()->id);
        // $request->user()->chirps()->create($validated);
        Chirp::create(['user_id'=>$request->user()->id,'message' => $validated['message']]);
        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        Gate::authorize('update', $chirp);
 
        return view('chirps.edit', [
            'chirp' => $chirp,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {
        Gate::authorize('update', $chirp);  // doubt what is this gate
 
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
        $chirp->update($validated);
 
        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        Gate::authorize('delete', $chirp);
        $chirp->delete();
 
        return redirect(route('chirps.index'));
    }
}
