<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo\StoreRequest;
use App\Http\Requests\Todo\UpdateRequest;
use App\Models\Checklist;
use App\Models\Todo;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TodoController extends Controller {
    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request, Checklist $checklist): RedirectResponse {
        $todo = new Todo();
        $todo->title = $request->title;
        $todo->checklist()->associate($checklist);
        $todo->save();

        return redirect()->back()->with("success", __("todo.store_success"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Todo  $todo
     * @return View
     */
    public function edit(Todo $todo): View {
        $this->authorize("update", $todo);

        return view("todo.edit", compact("todo"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  Todo $todo
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, Todo $todo): RedirectResponse {
        $todo->update($request->all());

        return redirect()->route("dashboard")->with("success", __("todo.update_success"));
    }

    /**
     * Show the form for deleting the specified resource.
     *
     * @param  Todo  $todo
     * @return View
     */
    public function delete(Todo $todo): View {
        $this->authorize("delete", $todo);

        return view("todo.delete", compact("todo"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Todo $todo
     * @return RedirectResponse
     */
    public function destroy(Todo $todo): RedirectResponse {
        $this->authorize("delete", $todo);
        $todo->delete();

        return redirect()->route("dashboard")->with("success", __("todo.delete_success"));
    }
}
