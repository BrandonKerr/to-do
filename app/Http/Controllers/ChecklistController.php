<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checklist\StoreRequest;
use App\Http\Requests\Checklist\UpdateRequest;
use App\Models\Checklist;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChecklistController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(User $user, $all = false): View {
        $this->authorize("view", $user);
        $checklists = $user->checklists()
            ->when($all, function ($query) {
                return $query->withTrashed();
            })
            ->when(! $all, function ($query) {
                return $query->complete();
            })
            ->get();

        return view("checklist.index", compact("user", "checklists", "all"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View {
        $this->authorize("create", Checklist::class);

        return view("checklist.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse {
        $checklist = new Checklist();
        $checklist->title = $request->title;
        $checklist->user()->associate(Auth::user());
        $checklist->save();

        return redirect()->route("dashboard")->with("success", __("checklist.store_success"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Checklist  $checklist
     * @return View
     */
    public function edit(Checklist $checklist): View {
        $this->authorize("update", $checklist);

        return view("checklist.edit", compact("checklist"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  Checklist $checklist
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, Checklist $checklist): RedirectResponse {
        $checklist->update($request->all());

        return redirect()->route("dashboard")->with("success", __("checklist.update_success"));
    }

    /**
     * Show the form for deleting the specified resource.
     *
     * @param  Checklist  $checklist
     * @return View
     */
    public function delete(Checklist $checklist): View {
        $this->authorize("delete", $checklist);

        return view("checklist.delete", compact("checklist"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Checklist $checklist
     * @return RedirectResponse
     */
    public function destroy(Checklist $checklist): RedirectResponse {
        $this->authorize("delete", $checklist);
        $checklist->delete();

        return redirect()->route("dashboard")->with("success", __("checklist.delete_success"));
    }
}
