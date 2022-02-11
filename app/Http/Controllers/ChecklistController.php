<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checklist\StoreRequest;
use App\Http\Requests\Checklist\UpdateRequest;
use App\Models\Checklist;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO show all checklist (including completed)
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Checklist::class);
        return view('checklist.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $checklist = new Checklist();
        $checklist->title = $request->title;
        $checklist->user()->associate(Auth::user());
        $checklist->save();

        return redirect()->route('dashboard')->with('success', __('checklist.store_success'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function edit(Checklist $checklist)
    {
        $this->authorize('update', $checklist);

        return view('checklist.edit', compact('checklist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Checklist $checklist
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Checklist $checklist)
    {
        $checklist->update($request->all());

        return redirect()->route('dashboard')->with('success', __('checklist.update_success'));
    }

    /**
     * Show the form for deleting the specified resource.
     *
     * @param  Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function delete(Checklist $checklist)
    {
        $this->authorize('delete', $checklist);

        return view('checklist.delete', compact('checklist'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Checklist $checklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checklist $checklist)
    {
        $this->authorize('delete', $checklist);
        $checklist->delete();

        return redirect()->route('dashboard')->with('success', __('checklist.delete_success'));
    }
}
