<?php

namespace Tests\Unit;

use App\Models\Definitions\TodoDefinition;
use App\Models\Enums\RoleEnum;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase {
    /**
     * Ensure the Role Display attribute returns the right value
     *
     * @return void
     * @test
     */
    public function roleDisplayWorks(): void {
        $user = User::factory()->create();
        $admin = User::factory()->admin()->create();

        $this->assertSame($user->roleDisplay, RoleEnum::User->value);
        $this->assertSame($admin->roleDisplay, RoleEnum::Admin->value);
    }

    /**
     * Ensure the Checklists relationship works
     *
     * @return void
     * @test
     */
    public function checklistRelationshipWorks(): void {
        $user = User::factory()
            ->withChecklist("list one")
            ->withChecklist("list two")
            ->create();

        $user->load("checklists");

        $this->assertCount(2, $user->checklists);
        $this->assertSame("list one", $user->checklists->first()->title);
        $this->assertSame("list two", $user->checklists->last()->title);
    }

    /**
     * Ensure the Todos relationship works
     *
     * @return void
     * @test
     */
    public function todosRelationshipWorks(): void {
        $user = User::factory()
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

        $user->load("checklists", "todos");

        $this->assertCount(5, $user->todos);
        $this->assertSame("item 1", $user->todos->first()->title);
        $this->assertTrue($user->todos->first()->is_complete);
        $this->assertSame("list two item 3", $user->todos->last()->title);
        $this->assertFalse($user->todos->last()->is_complete);
    }

    /**
     * Ensure soft deleting the user also soft deletes the checklists
     *
     * @return void
     * @test
     */
    public function deleteCascadesToChecklist(): void {
        $user = User::factory()
            ->withChecklist("list one", [
                new TodoDefinition("item 1", true),
            ])
            ->create();

        $user->load("checklists", "todos");
        $checklist = $user->checklists->first();
        $todo = $user->todos->first();

        $this->assertNotSoftDeleted($user);
        $this->assertNotSoftDeleted($checklist);
        $this->assertNotSoftDeleted($todo);

        $user->delete();

        $this->assertSoftDeleted($user);
        $this->assertSoftDeleted($checklist);
        $this->assertSoftDeleted($todo);
    }

    /**
     * Ensure restoring a soft deleted user also restores their checklists
     *
     * @return void
     * @test
     */
    public function restoreCascadesToChecklist(): void {
        $user = User::factory()
            ->withChecklist("list one", [
                new TodoDefinition("item 1", true),
            ])
            ->create();

        $user->load("checklists", "todos");
        $checklist = $user->checklists->first();
        $todo = $user->todos->first();

        $user->delete();
        $this->assertSoftDeleted($user);
        $this->assertSoftDeleted($checklist);
        $this->assertSoftDeleted($todo);

        $user->restore();

        $this->assertNotSoftDeleted($user);
        $this->assertNotSoftDeleted($checklist);
        $this->assertNotSoftDeleted($todo);
    }
}
