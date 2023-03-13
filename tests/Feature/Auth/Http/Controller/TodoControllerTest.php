<?php

namespace Tests\Feature\Http\Controller;

use App\Models\Checklist;
use App\Models\Definitions\TodoDefinition;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoControllerTest extends TestCase {
    use WithFaker;

    protected User $admin;

    protected User $user1;

    protected User $user2;

    protected function setUp(): void {
        parent::setUp();
        $this->admin = User::factory([
            "is_admin" => true,
        ])
        ->withChecklist("admin list", [
            new TodoDefinition("first item", false),
        ])
        ->create();

        $this->user1 = User::factory()
        ->withChecklist("list one", [
            new TodoDefinition("item 1", true),
            new TodoDefinition("item 2", false),
        ])
        ->withChecklist("completed list",
            [
                new TodoDefinition("completed item 1", true),
                new TodoDefinition("completed item 2", true),
                new TodoDefinition("completed item 3", true),
            ])
        ->withChecklist("deleted list",
            [
                new TodoDefinition("deleted item 1", true),
            ])
        ->create();
        $deleted = Checklist::firstWhere("title", "deleted list");
        $deleted->delete();

        $this->user2 = User::factory()
        ->withChecklist("My list!", [
            new TodoDefinition("Add an item", true),
            new TodoDefinition("Complete an item", false),
        ])
        ->create();
    }

    /**
     * Ensure validation works for the store route:
     * - admin can create a Todo for any checklist
     * - normal user can only create a Todo for a Checlist that belongs to them
     * - title is required and max 255 characters
     *
     * @return void
     * @test
     */
    public function storeValidationWorks(): void {
        // authZ checks
        $this->actingAs($this->admin);
        // admin creating for self
        $checklist = $this->admin->checklists->first();
        $response = $this->put(route("todo.store", $checklist), [
            "title" => "New item",
        ]);
        $response->assertRedirect();

        // admin creating for another user
        $checklist = $this->user1->checklists->first();
        $response = $this->put(route("todo.store", $checklist), [
            "title" => "New item",
        ]);
        $response->assertRedirect();

        // normal user creating for self
        $this->actingAs($this->user1);
        $checklist = $this->user1->checklists->first();
        $response = $this->put(route("todo.store", $checklist), [
            "title" => "New item",
        ]);
        $response->assertRedirect();

        // normal user creating another user
        $this->actingAs($this->user1);
        $checklist = $this->user2->checklists->first();
        $response = $this->put(route("todo.store", $checklist), [
            "title" => "New item",
        ]);
        $response->assertForbidden();

        // title validation
        $this->actingAs($this->user2);
        $checklist = $this->user2->checklists->first();
        $originalTitle = $checklist->title;

        // required
        $response = $this->put(route("todo.store", $checklist), [
        ]);
        $response->assertSessionHasErrors([
            "title" => __("todo.title_required"),
        ]);
        // too long
        $response = $this->put(route("todo.store", $checklist), [
            "title" => $this->faker->text(500),
        ]);
        $response->assertSessionHasErrors([
            "title" => __("todo.title_max", ["max" => 255]),
        ]);
    }

    /**
     * Ensure the store route works
     *
     * @return void
     * @test
     */
    public function storeWorks(): void {
        $this->actingAs($this->user1);
        $checklist = $this->user1->checklists->first();
        $todoCount = $checklist->todos->count();

        $response = $this->put(route("todo.store", $checklist), [
            "title" => "New item",
        ]);
        $response->assertRedirect();
        $response->assertSessionHas("success", __("todo.store_success"));

        $checklist->refresh();
        $this->assertCount($todoCount + 1, $checklist->todos);
        $newTodo = $checklist->todos->sortByDesc("id")->first();
        $this->assertSame("New item", $newTodo->title);
    }

    /**
     * Ensure admin users can edit anyone's todo,
     * and normal users can only edit their own
     *
     * @return void
     * @test
     */
    public function editAuthorizationWorks(): void {
        $this->actingAs($this->admin);
        $todo = $this->admin->todos->first();
        // admin editing own todo
        $response = $this->get(route("todo.edit", $todo));
        $response->assertOk();
        // admin editing another user's todo
        $todo = $this->user1->todos->first();
        $response = $this->get(route("todo.edit", $todo));
        $response->assertOk();

        $this->actingAs($this->user1);
        // normal user editing own todo
        $response = $this->get(route("todo.edit", $todo));
        $response->assertOk();
        // normal user editing another user's todo
        $todo = $this->user2->todos->first();
        $response = $this->get(route("todo.edit", $todo));
        $response->assertForbidden();
    }

    /**
     * Ensure normal and admin users can access the edit route
     *
     * @return void
     * @test
     */
    public function editWorks(): void {
        $todo = $this->user1->todos->first();
        $this->actingAs($this->user1);

        $response = $this->get(route("todo.edit", $todo));
        $response->assertViewIs("todo.edit");
        $response->assertViewHas("todo", $todo);
    }

    /**
     * Ensure validation works for the update route:
     * - admin user can update anyone's todo
     * - normal users can only update their own todo
     * - title is required and max 255 characters
     *
     * @return void
     * @test
     */
    public function updateValidationWorks(): void {
        // authZ checks
        $this->actingAs($this->admin);
        $todo = $this->admin->todos->first();
        // admin updating own todo
        $response = $this->patch(route("todo.update", $todo), [
            "title" => "Updated item",
        ]);
        $response->assertRedirectToRoute("dashboard");
        // admin updating another user's todo
        $todo = $this->user1->todos->first();
        $response = $this->patch(route("todo.update", $todo), [
            "title" => "Updated item",
        ]);
        $response->assertRedirectToRoute("dashboard");

        $this->actingAs($this->user1);
        // normal user updating their own todo
        $response = $this->patch(route("todo.update", $todo), [
            "title" => "Updated item",
        ]);
        $response->assertRedirectToRoute("dashboard");

        // normal user updating another user's todo
        $todo = $this->user2->todos->first();
        $response = $this->patch(route("todo.update", $todo), [
            "title" => "Updated item",
        ]);
        $response->assertForbidden();

        // title validation
        $this->actingAs($this->user2);
        $todo = $this->user2->todos->first();

        // required
        $response = $this->patch(route("todo.update", $todo), [
        ]);
        $response->assertSessionHasErrors([
            "title" => __("todo.title_required"),
        ]);
        // too long
        $response = $this->patch(route("todo.update", $todo), [
            "title" => $this->faker->text(500),
        ]);
        $response->assertSessionHasErrors([
            "title" => __("todo.title_max", ["max" => 255]),
        ]);
    }

    /**
     * Ensure the update route works
     *
     * @return void
     * @test
     */
    public function updateWorks(): void {
        $this->actingAs($this->user1);
        $todo = $this->user1->todos->first();
        $originalTitle = $todo->title;

        $response = $this->patch(route("todo.update", $todo), [
            "title" => "Updated item",
        ]);
        $response->assertRedirectToRoute("dashboard");
        $response->assertSessionHas("success", __("todo.update_success"));

        $todo->refresh();
        $this->assertNotSame($originalTitle, $todo->title);
        $this->assertSame("Updated item", $todo->title);
    }

    /**
     * Ensure admin users can access the delete page for anyone's todo,
     * and normal users can only access the delete page for their own
     *
     * @return void
     * @test
     */
    public function deleteAuthorizationWorks(): void {
        $this->actingAs($this->admin);
        $todo = $this->admin->todos->first();
        // admin deleting own todo
        $response = $this->get(route("todo.delete", $todo));
        $response->assertOk();
        // admin deleting another user's todo
        $todo = $this->user1->todos->first();
        $response = $this->get(route("todo.delete", $todo));
        $response->assertOk();

        $this->actingAs($this->user1);
        // normal user deleting own todo
        $response = $this->get(route("todo.delete", $todo));
        $response->assertOk();
        // normal user deleting another user's todo
        $todo = $this->user2->todos->first();
        $response = $this->get(route("todo.delete", $todo));
        $response->assertForbidden();
    }

    /**
     * Ensure the delete route returns the appropriate view and data
     *
     * @return void
     * @test
     */
    public function deleteWorks(): void {
        $todo = $this->user1->todos->first();
        $this->actingAs($this->user1);

        $response = $this->get(route("todo.delete", $todo));
        $response->assertViewIs("todo.delete");
        $response->assertViewHas("todo", $todo);
    }

    /**
     * Ensure admin users can delete anyone's todo,
     * and normal users can only delete their own
     *
     * @return void
     * @test
     */
    public function destroyAuthorizationWorks(): void {
        $this->actingAs($this->admin);
        $todo = $this->admin->todos->first();
        // admin deleting own todo
        $response = $this->delete(route("todo.destroy", $todo));
        $response->assertRedirectToRoute("dashboard");
        // admin deleting another user's todo
        $todo = $this->user1->todos->first();
        $response = $this->delete(route("todo.destroy", $todo));
        $response->assertRedirectToRoute("dashboard");

        $this->actingAs($this->user1);
        // normal user deleting own todo
        $todo = $this->user1->todos->last();
        $response = $this->delete(route("todo.destroy", $todo));
        $response->assertRedirectToRoute("dashboard");
        // normal user deleting another user's todo
        $todo = $this->user2->todos->first();
        $response = $this->delete(route("todo.destroy", $todo));
        $response->assertForbidden();
    }

    /**
     * Ensure admin users can delete anyone's todo,
     * and normal users can only delete their own
     *
     * @return void
     * @test
     */
    public function destroyWorks(): void {
        $todo = $this->user1->todos->first();
        $this->actingAs($this->user1);

        $response = $this->delete(route("todo.destroy", $todo));
        $response->assertRedirectToRoute("dashboard");
        $response->assertSessionHas("success", __("todo.delete_success"));
        $this->assertSoftDeleted($todo);
    }
}
