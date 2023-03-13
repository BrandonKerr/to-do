<?php

namespace Tests\Feature\Http\Controller;

use App\Models\Checklist;
use App\Models\Definitions\TodoDefinition;
use App\Models\User;
use Tests\TestCase;

class DashboardControllerTest extends TestCase {
    protected User $user1;

    protected User $user2;

    protected function setUp(): void {
        parent::setUp();

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
     * Ensure the dashboard is for the authenticated user and
     * shows their incomplete lists
     *
     * @return void
     * @test
     */
    public function dashboardWorks(): void {
        $this->actingAs($this->user1);
        $response = $this->get(route("dashboard"));
        $response->assertOk();
        $response->assertViewHasAll([
            "user" => $this->user1,
            "checklists" => $this->user1->checklists()->incomplete()->get(),
        ]);
    }
}
