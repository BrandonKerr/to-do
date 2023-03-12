<?php

namespace Tests\Unit;

use App\Models\Checklist;
use App\Models\Definitions\TodoDefinition;
use App\Models\User;
use Tests\TestCase;

class ChecklistTest extends TestCase {
    /**
     * Ensure the User relationship works
     *
     * @return void
     * @test
     */
    public function userRelationshipWorks(): void {
        $user = User::factory()
            ->withChecklist("list one")
            ->create();

        $checklist = Checklist::first();
        $checklist->load("user");

        $this->assertSame($user->id, $checklist->user->id);
    }

    /**
     * Ensure the Todos relationship works
     *
     * @return void
     * @test
     */
    public function todosRelationshipWorks(): void {
        User::factory()
            ->withChecklist("list one", [
                new TodoDefinition("item 1", true),
                new TodoDefinition("item 2", false),
            ])
            ->withChecklist("list two",
                [
                    new TodoDefinition("list two item 1", true),
                    new TodoDefinition("list two item 2", true),
                    new TodoDefinition("list two item 3", false),
                ])
            ->create();
        $checklist1 = Checklist::firstWhere("title", "list one");
        $checklist2 = Checklist::firstWhere("title", "list two");

        $this->assertCount(2, $checklist1->todos);
        $this->assertCount(3, $checklist2->todos);
        $this->assertSame("item 1", $checklist1->todos->first()->title);
        $this->assertTrue($checklist1->todos->first()->is_complete);
        $this->assertSame("list two item 3", $checklist2->todos->last()->title);
        $this->assertFalse($checklist2->todos->last()->is_complete);
    }

    /**
     * Ensure the Complete scope works
     *
     * @return void
     * @test
     */
    public function completeScopeWorks(): void {
        User::factory()
        ->withChecklist("completed list", [
            new TodoDefinition("item 1", true),
            new TodoDefinition("item 2", true),
        ])
        ->withChecklist("incomplete list",
            [
                new TodoDefinition("list two item 1", true),
                new TodoDefinition("list two item 2", true),
                new TodoDefinition("list two item 3", false),
            ])
        ->withChecklist("empty list")
        ->create();

        $completeChecklists = Checklist::complete()->get();
        $this->assertCount(1, $completeChecklists);
        $this->assertSame("completed list", $completeChecklists->first()->title);
        $this->assertCount(2, $completeChecklists->first()->todos);
    }

    /**
     * Ensure the Incomplete scope works
     *
     * @return void
     * @test
     */
    public function incompleteScopeWorks(): void {
        User::factory()
        ->withChecklist("completed list", [
            new TodoDefinition("item 1", true),
            new TodoDefinition("item 2", true),
        ])
        ->withChecklist("incomplete list",
            [
                new TodoDefinition("list two item 1", true),
                new TodoDefinition("list two item 2", true),
                new TodoDefinition("list two item 3", false),
            ])
        ->withChecklist("empty list")
        ->create();

        $incompleteChecklists = Checklist::incomplete()->get();
        $this->assertCount(2, $incompleteChecklists);
        $this->assertSame("incomplete list", $incompleteChecklists->first()->title);
        $this->assertCount(3, $incompleteChecklists->first()->todos);
        $this->assertSame("empty list", $incompleteChecklists->last()->title);
        $this->assertEmpty($incompleteChecklists->last()->todos);
    }
}
