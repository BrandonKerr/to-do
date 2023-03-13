<?php

namespace Tests\Feature\Http\Controller;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase {
    use WithFaker;

    protected User $admin;

    protected User $user1;

    protected User $user2;

    protected function setUp(): void {
        parent::setUp();
        $this->admin = User::factory([
            "is_admin" => true,
        ])
        ->create();
        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();
        $deleted = User::factory()->create();
        $deleted->delete();
    }

    /**
     * Ensure only the admin user can access the users index
     *
     * @return void
     * @test
     */
    public function indexAuthorizationWorks(): void {
        $this->actingAs($this->admin);
        $response = $this->get(route("user.index"));
        $response->assertOk();

        $this->actingAs($this->user1);
        $response = $this->get(route("user.index"));
        $response->assertForbidden();
    }

    /**
     * Ensure all of the users are displayed on the index route
     *
     * @return void
     * @test
     */
    public function indexRouteShowsAllUsers(): void {
        $this->actingAs($this->admin);
        $response = $this->get(route("user.index"));

        $response->assertViewIs("user.index");
        $response->assertViewHasAll([
            "users" => User::withTrashed()->get(),
        ]);
    }

    /**
     * Ensure admin users can edit user,
     * and normal users can only edit themself
     *
     * @return void
     * @test
     */
    public function editAuthorizationWorks(): void {
        $this->actingAs($this->admin);
        // admin editing self
        $response = $this->get(route("user.edit", $this->admin));
        $response->assertOk();
        // admin editing another user
        $response = $this->get(route("user.edit", $this->user1));
        $response->assertOk();

        $this->actingAs($this->user1);
        // normal user editing self
        $response = $this->get(route("user.edit", $this->user1));
        $response->assertOk();
        // normal user editing another user's list
        $response = $this->get(route("user.edit", $this->user2));
        $response->assertForbidden();
    }

    /**
     * Ensure the edit route returns the appropriate view and data
     *
     * @return void
     * @test
     */
    public function editWorks(): void {
        $this->actingAs($this->user1);

        $response = $this->get(route("user.edit", $this->user1));
        $response->assertViewIs("user.edit");
        $response->assertViewHas("user", $this->user1);
    }

    /**
     * Ensure validation works for the update route:
     * - admin user can update anyone
     * - normal users can only update themself
     * - name is required and max 255 characters
     * - email is required and must be an email address
     *
     * @return void
     * @test
     */
    public function updateValidationWorks(): void {
        // authZ checks
        $this->actingAs($this->admin);
        $user = $this->admin;
        // admin updating self
        $response = $this->patch(route("user.update", $user), [
            "name" => $this->faker()->name,
            "email" => $this->faker()->email,
        ]);
        $response->assertRedirectToRoute("dashboard");
        // admin updating another user
        $user = $this->user1;
        $response = $this->patch(route("user.update", $user), [
            "name" => $this->faker()->name,
            "email" => $this->faker()->email,
        ]);
        $response->assertRedirectToRoute("user.index");

        $this->actingAs($this->user1);
        // normal user updating themself
        $response = $this->patch(route("user.update", $user), [
            "name" => $this->faker()->name,
            "email" => $this->faker()->email,
        ]);
        $response->assertRedirectToRoute("dashboard");

        // normal user updating another user
        $user = $this->user2;
        $response = $this->patch(route("user.update", $user), [
            "name" => $this->faker()->name,
            "email" => $this->faker()->email,
        ]);
        $response->assertForbidden();

        // name validation
        $this->actingAs($this->user2);
        $user = $this->user2;

        // required
        $response = $this->patch(route("user.update", $user), [
            "email" => $this->faker()->email,
        ]);
        $response->assertSessionHasErrors([
            "name" => __("user.name_required"),
        ]);
        // too long
        $response = $this->patch(route("user.update", $user), [
            "name" => $this->faker->text(500),
            "email" => $this->faker()->email,
        ]);
        $response->assertSessionHasErrors([
            "name" => __("user.name_max", ["max" => 255]),
        ]);

        // email validation
        // required
        $response = $this->patch(route("user.update", $user), [
            "name" => $this->faker()->name,
        ]);
        $response->assertSessionHasErrors([
            "email" => __("user.email_required"),
        ]);
        // not email
        $response = $this->patch(route("user.update", $user), [
            "name" => $this->faker->name,
            "email" => "foo",
        ]);
        $response->assertSessionHasErrors([
            "email" => __("user.email_email"),
        ]);
    }

    /**
     * Ensure the update route works
     *
     * @return void
     * @test
     */
    public function updateWorks(): void {
        //  updating self
        $this->actingAs($this->user1);
        $user = $this->user1;
        $originalName = $user->name;
        $originalEmail = $user->email;

        $response = $this->patch(route("user.update", $user), [
            "name" => "Foo Bar",
            "email" => "foo@bar.com",
        ]);
        $response->assertRedirectToRoute("dashboard");
        $response->assertSessionHas("success", __("user.me_update_success"));

        $user->refresh();
        $this->assertNotSame($originalName, $user->name);
        $this->assertSame("Foo Bar", $user->name);
        $this->assertNotSame($originalEmail, $user->email);
        $this->assertSame("foo@bar.com", $user->email);

        // updating someone else
        $this->actingAs($this->admin);
        $user = $this->user2;
        $originalName = $user->name;
        $originalEmail = $user->email;

        $response = $this->patch(route("user.update", $user), [
            "name" => "Foo Two",
            "email" => "foo@two.com",
        ]);
        $response->assertRedirectToRoute("user.index");
        $response->assertSessionHas("success", __("user.update_success"));

        $user->refresh();
        $this->assertNotSame($originalName, $user->name);
        $this->assertSame("Foo Two", $user->name);
        $this->assertNotSame($originalEmail, $user->email);
        $this->assertSame("foo@two.com", $user->email);
    }

    /**
     * Ensure only admin users can delete anyone,
     * and normal users can only delete themself
     *
     * @return void
     * @test
     */
    public function deleteAuthorizationWorks(): void {
        // normal user deleting another user
        $this->actingAs($this->user1);
        $response = $this->get(route("user.delete", $this->user2));
        $response->assertForbidden();
        // normal user deleting self
        $response = $this->get(route("user.delete", $this->user1));
        $response->assertOk();

        $this->actingAs($this->admin);
        // admin deleting another user
        $response = $this->get(route("user.delete", $this->user2));
        $response->assertOk();
        // admin deleting self
        $response = $this->get(route("user.delete", $this->admin));
        $response->assertOk();
    }

    /**
     * Ensure the delete route returns the appropriate view and data
     *
     * @return void
     * @test
     */
    public function deleteWorks(): void {
        $user = $this->user1;
        $this->actingAs($this->admin);

        $response = $this->get(route("user.delete", $user));
        $response->assertViewIs("user.delete");
        $response->assertViewHas("user", $user);
    }

    /**
     * Ensure admin users can delete anyone,
     * and normal users can only delete themself
     *
     * @return void
     * @test
     */
    public function destroyAuthorizationWorks(): void {
        // normal user deleting another user
        $this->actingAs($this->user1);
        $response = $this->delete(route("user.destroy", $this->user2));
        $response->assertForbidden();
        // normal user deleting self
        $response = $this->delete(route("user.destroy", $this->user1));
        $response->assertRedirectToRoute("user.index");

        $this->actingAs($this->admin);
        // admin deleting another user
        $response = $this->delete(route("user.destroy", $this->user2));
        $response->assertRedirectToRoute("user.index");
        // admin deleting self
        $response = $this->delete(route("user.destroy", $this->admin));
        $response->assertRedirectToRoute("user.index");
    }

    /**
     * Ensure admin users can delete anyone,
     * and normal users can only delete themself
     *
     * @return void
     * @test
     */
    public function destroyWorks(): void {
        $user = $this->user1;
        $this->actingAs($this->admin);

        $response = $this->delete(route("user.destroy", $user));
        $response->assertRedirectToRoute("user.index");
        $response->assertSessionHas("success", __("user.delete_success"));
        $this->assertSoftDeleted($user);
    }
}
