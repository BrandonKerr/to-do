<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo\StoreRequest;
use App\Http\Requests\Todo\UpdateRequest;
use App\Models\Checklist;
use App\Models\Todo;

class TodoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Checklist $checklist)
    {
        $todo = new Todo();
        $todo->title = $request->title;
        $todo->checklist()->associate($checklist);
        $todo->save();

        return redirect()->back()->with('success', __('todo.store_success'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Todo  $checklist
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $todo)
    {
        $this->authorize('update', $todo);

        return view('todo.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Todo $todo)
    {
        $todo->update($request->all());

        return redirect()->route('dashboard')->with('success', __('todo.update_success'));
    }

    /**
     * Show the form for deleting the specified resource.
     *
     * @param  Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function delete(Todo $todo)
    {
        $this->authorize('delete', $todo);

        return view('todo.delete', compact('todo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $this->authorize('delete', $todo);
        $todo->delete();

        return redirect()->route('dashboard')->with('success', __('todo.delete_success'));
    }
}
