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
     * 
     *
     * @return void
     */
    public function test_post_project()
    {
        $projectData = [
            'name' => 'テストプロジェクト',
            'uuid' => '',
        ];

        $this->withoutMiddleware()
            ->json('POST', 'api/project', $projectData)
            ->assertStatus(201)
            ->assertJsonStructure([
                'project' => [
                    'name',
                    'uuid'
                ],
                "message",
                "error",
            ])
            ->assertJsonPath('project.name', 'テストプロジェクト')
            ->assertJsonPath('message', '新しいプロジェクトの追加を完了しました')
            ->assertJsonPath('error', '');
    }
}
