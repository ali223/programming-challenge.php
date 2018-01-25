<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use \Laravel\Lumen\Testing\DatabaseMigrations;

class CreateTasksTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_add_a_new_task_in_json_api_format()
    {
        $task = factory(Task::class)->make();

        $response = $this->call('POST', "/api/tasks", [
                        'data' => [
                            'type' => 'tasks',
                            'attributes' => [
                                'title' => $task->title,
                                'completed' => (bool) $task->completed
                            ],
                        ]
                    ]);

        $this->assertEquals(201, $response->status());

        $this->seeJsonEquals([
            'data' => [
                'type' => 'tasks',
                'id' => "1",
                'attributes' => [
                    'title' => $task->title,
                    'completed' => (bool) $task->completed
                ],
                'links' => [
                    'self' => url("/api/tasks/1")
                ]
            ]
        ]);

        $this->seeInDatabase('tasks', [
            'title' => $task->title
        ]);
    }

    /** @test */
    public function when_adding_a_new_task_title_and_completed_values_are_required()
    {
        $task = factory(Task::class)->make();

        $response = $this->call('POST', "/api/tasks", [
                        'data' => [
                            'type' => 'tasks',
                            'attributes' => [
                                'title' => '',
                                'completed' => ''
                            ],
                        ]
                    ]);

        $this->assertEquals(422, $response->status());

        $this->seeJson([
            'status' => 422,
            'title' => 'The given data was invalid.'
        ]);
    }
}
