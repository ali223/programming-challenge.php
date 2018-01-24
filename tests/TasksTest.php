<?php

namespace Tests;

use App\Task;
use \Laravel\Lumen\Testing\DatabaseMigrations;

class TasksTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_view_a_single_task_in_json_api_format()
    {
        $task = factory(Task::class)->create();

        $this->json('GET', "/api/tasks/{$task->id}")
            ->seeJson([
                        'data' => [
                            'type' => 'tasks',
                            'id' => (string) $task->id,
                            'attributes' => [
                                'title' => $task->title,
                                'completed' => $task->completed
                            ]
                        ]
                    ]);
    }
}
