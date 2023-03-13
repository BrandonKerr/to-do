<?php

namespace Tests\Feature\Http\Controller;

use App\Models\Checklist;
use App\Models\Definitions\TodoDefinition;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChecklistControllerTest extends TestCase {
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
     * Ensure admin user can view any user's index,
     * and normal users can only view their own
     *
     * @return void
     * @test
     */
    public function indexAuthorizationWorks(): void {
        $this->actingAs($this->admin);
        // admin viewing self
        $response = $this->get(route("list.index", $this->admin));
        $response->assertOk();
        // admin viewing another user
        $response = $this->get(route("list.index", $this->user1));
        $response->assertOk();

        $this->actingAs($this->user1);
        // normal user viewing self
        $response = $this->get(route("list.index", $this->user1));
        $response->assertOk();
        // normal user viewing another user
        $response = $this->get(route("list.index", $this->user2));
        $response->assertForbidden();
    }

    /**
     * Ensure only completed lists are displayed on the index route by default
     *
     * @return void
     * @test
     */
    public function indexRouteShowsOnlyCompletedLists(): void {
        $this->actingAs($this->user1);
        $response = $this->get(route("list.index", $this->user1));
        $response->assertViewIs("checklist.index");

        $response->assertViewHasAll([
            "user" => $this->user1,
            "all" => false,
            "checklists" => $this->user1->checklists()->complete()->get(),
        ]);
    }

    /**
     * Ensure all of the user's lists are displayed on the index route when selected
     *
     * @return void
     * @test
     */
    public function indexRouteShowsAllListsWhenSelected(): void {
        $this->actingAs($this->user1);
        $response = $this->get(route("list.index", ["user" => $this->user1, "all" => true]));
        $response->assertViewIs("checklist.index");

        $response->assertViewHasAll([
            "user" => $this->user1,
            "all" => true,
            "checklists" => $this->user1->checklists()->withTrashed()->get(),
        ]);
    }

    /**
     * Ensure only completed lists are displayed on the complete route
     *
     * @return void
     * @test
     */
    public function completeRouteShowsOnlyCompletedLists(): void {
        $this->actingAs($this->user1);
        $response = $this->get(route("list.complete", $this->user1));
        $response->assertViewIs("checklist.index");

        $response->assertViewHasAll([
            "user" => $this->user1,
            "all" => false,
            "checklists" => $this->user1->checklists()->complete()->get(),
        ]);
    }

    /**
     * Ensure normal and admin users can access the create route
     *
     * @return void
     * @test
     */
    public function createWorks(): void {
        $this->actingAs($this->admin);
        $response = $this->get(route("list.create"));
        $response->assertOk();
        $response->assertViewIs("checklist.create");

        $this->actingAs($this->user1);
        $response = $this->get(route("list.create"));
        $response->assertOk();
        $response->assertViewIs("checklist.create");
    }

    /**
     * Ensure validation works for the store route:
     * - any user can create list
     * - title is required and max 255 characters
     *
     * @return void
     * @test
     */
    public function storeValidationWorks(): void {
        // authZ checks
        $this->actingAs($this->admin);
        $response = $this->put(route("list.store"), [
            "title" => "New title",
        ]);
        $response->assertRedirectToRoute("dashboard");

        $this->actingAs($this->user1);
        $response = $this->put(route("list.store"), [
            "title" => "New title",
        ]);
        $response->assertRedirectToRoute("dashboard");

        // title validation
        $this->actingAs($this->user2);
        $checklist = $this->user2->checklists->first();
        $originalTitle = $checklist->title;

        // required
        $response = $this->put(route("list.store"), [
        ]);
        $response->assertSessionHasErrors([
            "title" => "The title field is required.",
        ]);
        // too long
        $response = $this->put(route("list.store"), [
            "title" => $this->faker->text(500),
        ]);
        $response->assertSessionHasErrors([
            "title" => "The title must not be greater than 255 characters.",
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
        $checklistCount = $this->user1->checklists->count();

        $response = $this->put(route("list.store"), [
            "title" => "New title",
        ]);
        $response->assertRedirectToRoute("dashboard");
        $response->assertSessionHas("success", __("checklist.store_success"));

        $this->user1->refresh();
        $this->assertCount($checklistCount + 1, $this->user1->checklists);
        $newChecklist = $this->user1->checklists->sortByDesc("id")->first();
        $this->assertSame("New title", $newChecklist->title);
    }

    /**
     * Ensure admin users can edit anyone's list,
     * and normal users can only edit their own
     *
     * @return void
     * @test
     */
    public function editAuthorizationWorks(): void {
        $this->actingAs($this->admin);
        $checklist = $this->admin->checklists->first();
        // admin editing own list
        $response = $this->get(route("list.edit", $checklist));
        $response->assertOk();
        // admin editing another user's list
        $checklist = $this->user1->checklists->first();
        $response = $this->get(route("list.edit", $checklist));
        $response->assertOk();

        $this->actingAs($this->user1);
        // normal user editing own list
        $response = $this->get(route("list.edit", $checklist));
        $response->assertOk();
        // normal user editing another user's list
        $checklist = $this->user2->checklists->first();
        $response = $this->get(route("list.edit", $checklist));
        $response->assertForbidden();
    }

    /**
     * Ensure normal and admin users can access the edit route
     *
     * @return void
     * @test
     */
    public function editWorks(): void {
        $checklist = $this->user1->checklists->first();
        $this->actingAs($this->user1);

        $response = $this->get(route("list.edit", $checklist));
        $response->assertViewIs("checklist.edit");
        $response->assertViewHas("checklist", $checklist);
    }

    /**
     * Ensure validation works for the update route:
     * - admin user can update anyone's list
     * - normal users can only update their own list
     * - title is required and max 255 characters
     *
     * @return void
     * @test
     */
    public function updateValidationWorks(): void {
        // authZ checks
        $this->actingAs($this->admin);
        $checklist = $this->admin->checklists->first();
        // admin updating own list
        $response = $this->patch(route("list.update", $checklist), [
            "title" => "Updated title",
        ]);
        $response->assertRedirectToRoute("dashboard");
        // admin updating another user's list
        $checklist = $this->user1->checklists->first();
        $response = $this->patch(route("list.update", $checklist), [
            "title" => "Updated title",
        ]);
        $response->assertRedirectToRoute("dashboard");

        $this->actingAs($this->user1);
        // normal user updating their own list
        $response = $this->patch(route("list.update", $checklist), [
            "title" => "Updated title",
        ]);
        $response->assertRedirectToRoute("dashboard");

        // normal user updating another user's list
        $checklist = $this->user2->checklists->first();
        $response = $this->patch(route("list.update", $checklist), [
            "title" => "Updated title",
        ]);
        $response->assertForbidden();

        // title validation
        $this->actingAs($this->user2);
        $checklist = $this->user2->checklists->first();

        // required
        $response = $this->patch(route("list.update", $checklist), [
        ]);
        $response->assertSessionHasErrors([
            "title" => "The title field is required.",
        ]);
        // too long
        $response = $this->patch(route("list.update", $checklist), [
            "title" => $this->faker->text(500),
        ]);
        $response->assertSessionHasErrors([
            "title" => "The title must not be greater than 255 characters.",
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
        $checklist = $this->user1->checklists->first();
        $originalTitle = $checklist->title;

        $response = $this->patch(route("list.update", $checklist), [
            "title" => "Updated title",
        ]);
        $response->assertRedirectToRoute("dashboard");
        $response->assertSessionHas("success", __("checklist.update_success"));

        $checklist->refresh();
        $this->assertNotSame($originalTitle, $checklist->title);
        $this->assertSame("Updated title", $checklist->title);
    }

    /**
     * Ensure admin users can access the delete page for anyone's list,
     * and normal users can only access the delete page for their own
     *
     * @return void
     * @test
     */
    public function deleteAuthorizationWorks(): void {
        $this->actingAs($this->admin);
        $checklist = $this->admin->checklists->first();
        // admin deleting own list
        $response = $this->get(route("list.delete", $checklist));
        $response->assertOk();
        // admin deleting another user's list
        $checklist = $this->user1->checklists->first();
        $response = $this->get(route("list.delete", $checklist));
        $response->assertOk();

        $this->actingAs($this->user1);
        // normal user deleting own list
        $response = $this->get(route("list.delete", $checklist));
        $response->assertOk();
        // normal user deleting another user's list
        $checklist = $this->user2->checklists->first();
        $response = $this->get(route("list.delete", $checklist));
        $response->assertForbidden();
    }

    /**
     * Ensure the delete route returns the appropriate view and data
     *
     * @return void
     * @test
     */
    public function deleteWorks(): void {
        $checklist = $this->user1->checklists->first();
        $this->actingAs($this->user1);

        $response = $this->get(route("list.delete", $checklist));
        $response->assertViewIs("checklist.delete");
        $response->assertViewHas("checklist", $checklist);
    }

    /**
     * Ensure admin users can delete anyone's list,
     * and normal users can only delete their own
     *
     * @return void
     * @test
     */
    public function destroyAuthorizationWorks(): void {
        $this->actingAs($this->admin);
        $checklist = $this->admin->checklists->first();
        // admin deleting own list
        $response = $this->delete(route("list.destroy", $checklist));
        $response->assertRedirectToRoute("dashboard");
        // admin deleting another user's list
        $checklist = $this->user1->checklists->first();
        $response = $this->delete(route("list.destroy", $checklist));
        $response->assertRedirectToRoute("dashboard");

        $this->actingAs($this->user1);
        // normal user deleting own list
        $checklist = $this->user1->checklists->last();
        $response = $this->delete(route("list.destroy", $checklist));
        $response->assertRedirectToRoute("dashboard");
        // normal user deleting another user's list
        $checklist = $this->user2->checklists->first();
        $response = $this->delete(route("list.destroy", $checklist));
        $response->assertForbidden();
    }

    /**
     * Ensure admin users can delete anyone's list,
     * and normal users can only delete their own
     *
     * @return void
     * @test
     */
    public function destroyWorks(): void {
        $checklist = $this->user1->checklists->first();
        $this->actingAs($this->user1);

        $response = $this->delete(route("list.destroy", $checklist));
        $response->assertRedirectToRoute("dashboard");
        $response->assertSessionHas("success", __("checklist.delete_success"));
        $this->assertSoftDeleted($checklist);
    }
}
