<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use \Laravel\Lumen\Testing\DatabaseMigrations;

class ViewTasksTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_view_a_single_task_in_json_api_format()
    {
        $task = factory(Task::class)->create();

        $this->json('GET', "/api/tasks/{$task->id}")
            ->seeJsonEquals([
                'data' => [
                    'type' => 'tasks',
                    'id' => (string) $task->id,
                    'attributes' => [
                        'title' => $task->title,
                        'completed' => (bool) $task->completed
                    ],
                    'links' => [
                        'self' => url("/api/tasks/{$task->id}")
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_user_can_view_all_tasks_in_json_api_format()
    {
        $tasks = factory(Task::class, 2)->create();

        $this->json('GET', "/api/tasks")
            ->seeJsonEquals([
                'links' => [
                    'self' => url('/api/tasks')
                ],
                'data' => [
                    [
                        'type' => 'tasks',
                        'id' => (string) $tasks[0]->id,
                        'attributes' => [
                            'title' => $tasks[0]->title,
                            'completed' => (bool) $tasks[0]->completed,
                        ],
                        'links' => [
                            'self' => url("/api/tasks/{$tasks[0]->id}")
                        ]
                    ],
                    [
                        'type' => 'tasks',
                        'id' => (string) $tasks[1]->id,
                        'attributes' => [
                            'title' => $tasks[1]->title,
                            'completed' => (bool) $tasks[1]->completed,
                        ],
                        'links' => [
                            'self' => url("/api/tasks/{$tasks[1]->id}")
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function when_a_user_tries_to_view_a_non_existing_task_a_404_response_is_returned()
    {        
        $response = $this->call('GET', "/api/tasks/12345");

        $this->assertEquals(404, $response->status());

        $this->seeJsonEquals([
            'errors' => [
                'status' => 404,
                'title' => 'Resource Not Found',
                'details' => 'Cannot find Resource with the id 12345'
            ]
        ]);
    }
}
