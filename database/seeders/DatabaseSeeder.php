<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;
use App\Models\Report;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Створюємо тестового користувача
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Створюємо ще 7 користувачів
        $users = User::factory(7)->create();

        // Додаємо тестового користувача до колекції
        $users->push($testUser);

        // Створюємо 6 проєктів з різними власниками
        $projects = collect();
        foreach ($users->random(6) as $owner) {
            $project = Project::factory()->create([
                'owner_id' => $owner->id,
            ]);
            $projects->push($project);

            // Додаємо власника як учасника проєкту з роллю 'owner'
            $project->users()->attach($owner->id, ['role' => 'owner']);

            // Додаємо 1-3 випадкових учасників з роллю 'member'
            $members = $users->where('id', '!=', $owner->id)->random(rand(1, 3));
            foreach ($members as $member) {
                $project->users()->attach($member->id, ['role' => 'member']);
            }
        }

        // Створюємо задачі у різних проєктах
        $tasks = collect();
        foreach ($projects as $project) {
            $projectUsers = $project->users;

            // Створюємо 1-2 задачі для кожного проєкту
            for ($i = 0; $i < rand(1, 2); $i++) {
                $task = Task::factory()->create([
                    'project_id' => $project->id,
                    'author_id' => $projectUsers->random()->id,
                    'assignee_id' => $projectUsers->random()->id,
                ]);
                $tasks->push($task);
            }
        }

        // Створюємо 8 коментарів до різних задач
        foreach ($tasks->random(min(8, $tasks->count())) as $task) {
            Comment::factory()->create([
                'task_id' => $task->id,
                'author_id' => $users->random()->id,
            ]);
        }

        // Створюємо 5 звітів
        Report::factory(5)->create();
    }
}
