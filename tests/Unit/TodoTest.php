<?php

namespace Tests\Unit;

use App\Models\Checklist;
use App\Models\Definitions\TodoDefinition;
use App\Models\Todo;
use App\Models\User;
use Tests\TestCase;

class TodoTest extends TestCase {
    /**
     * Ensure the User attribute works
     *
     * @return void
     * @test
     */
    public function userAttributeWorks(): void {
        $user = User::factory()
        ->withChecklist("list one", [
            new TodoDefinition("item 1", true),
            new TodoDefinition("item 2", false),
        ])
        ->create();

        $todo = Todo::first();

        $this->assertSame($user->id, $todo->user->id);
    }

    /**
     * Ensure the Checklist relationship works
     *
     * @return void
     * @test
     */
    public function checklistRelationshipWorks(): void {
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
        $todo1_1 = Todo::firstWhere("title", "item 1");
        $todo2_3 = Todo::firstWhere("title", "list two item 3");

        $this->assertSame($checklist1->id, $todo1_1->checklist->id);
        $this->assertSame($checklist2->id, $todo2_3->checklist->id);
    }

    /**
     * Ensure the Incomplete scope works
     *
     * @return void
     * @test
     */
    public function incompleteScopeWorks(): void {
        $user = User::factory()
        ->withChecklist("list one", [
            new TodoDefinition("item 1", true),
            new TodoDefinition("item 2", true),
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
        $this->assertCount(0, $checklist1->todos()->incomplete()->get());
        $this->assertCount(1, $checklist2->todos()->incomplete()->get());
        $this->assertSame("list two item 3", Todo::incomplete()->first()->title);
    }
}
