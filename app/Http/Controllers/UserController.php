<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View {
        $this->authorize("viewAny", User::class);
        $users = User::withTrashed()->get();

        return view("user.index", compact("users"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return View
     */
    public function edit(User $user): View {
        $this->authorize("update", $user);

        return view("user.edit", compact("user"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  User $user
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, User $user): RedirectResponse {
        $user->update($request->all());

        if ($user->is(Auth::user())) {
            return redirect()->route("dashboard")->with("success", __("user.me_update_success"));
        }

        return redirect()->route("user.index")->with("success", __("user.update_success"));
    }

    /**
     * Show the form for deleting the specified resource.
     *
     * @param  User  $user
     * @return View
     */
    public function delete(User $user): View {
        $this->authorize("delete", $user);

        return view("user.delete", compact("user"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse {
        $this->authorize("delete", $user);
        $user->delete();

        return redirect()->route("user.index")->with("success", __("user.delete_success"));
    }
}
