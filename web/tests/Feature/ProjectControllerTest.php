<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    /**
     * Post Project Test
     *
     * @return void
     */
    public function test_post_project()
    {
        $user = $this->post('api/login', [
            'email'    => 'aaa@aaa',
            'password' => 'aaa'
        ]);
        $user->assertStatus(200);

        $inputData = [
            'name' => 'テストプロジェクト',
            'uuid' => null,
        ];

        $response =  $this
                        ->json('POST', 'api/project', $inputData)
                        ->assertJsonStructure([
                            'project' => [
                                'name',
                                'uuid',
                            ],
                            "message",
                            "error",
                        ])
                        ->assertJsonPath('project.name', 'テストプロジェクト')
                        ->assertJsonPath('project.created_by_user_id', 1)
                        ->assertJsonPath('message', '新しいプロジェクトの追加を完了しました')
                        ->assertJsonPath('error', '');

        $response->assertStatus(201);
    }
}
