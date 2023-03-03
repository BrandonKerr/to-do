<?php

namespace Database\Seeders;

use App\Models\Checklist;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $user = User::firstWhere("is_admin", false);

        if (is_null($user)) {
            $this->command->error("No standard user found. Please run UsersSeeder.");

            return;
        }

        $progress = $this->command->getOutput()->createProgressBar(3);
        // new list that will show on the dashboard
        $checklist = new Checklist();
        $checklist->title = "My first list";
        $checklist->user()->associate($user);
        $checklist->save();
        $todo = new Todo();
        $todo->title = "Make a list";
        $todo->checklist()->associate($checklist);
        $todo->save();
        $todo = new Todo();
        $todo->title = "Check something off";
        $todo->is_complete = true;
        $todo->checklist()->associate($checklist);
        $todo->save();
        $progress->advance();

        // checklist with all todos completed
        $checklist = new Checklist();
        $checklist->title = "My completed list";
        $checklist->user()->associate($user);
        $checklist->save();
        $todo = new Todo();
        $todo->title = "Complete this list";
        $todo->is_complete = true;
        $todo->checklist()->associate($checklist);
        $todo->save();
        $progress->advance();

        // checklist (and todos that have been deleted)
        $checklist = new Checklist();
        $checklist->title = "My deleted list";
        $checklist->user()->associate($user);
        $checklist->save();
        $todo = new Todo();
        $todo->title = "Make another list";
        $todo->is_complete = true;
        $todo->checklist()->associate($checklist);
        $todo->save();
        $todo = new Todo();
        $todo->title = "Nevermind. Delete this list";
        $todo->checklist()->associate($checklist);
        $todo->save();
        $checklist->delete();
        $progress->advance();

        $progress->finish();
    }
}
