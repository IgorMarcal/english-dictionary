<?php

namespace Tests\Feature;

use App\Http\Services\CreateUserService;
use App\Models\Users;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mockery;

class CreateUserTest extends TestCase
{

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_user_creation_success(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testNewUser@example.com',
            'password' => 'password123',
        ];

        $request = new Request($data);

        $userMock = Mockery::mock(Users::class);
        $this->app->instance(Users::class, $userMock);
        $this->app->bind(Users::class, function() use ($userMock) {
            return $userMock;
        });

        $userMock->shouldReceive('where')
            ->with('email', '=', $data['email'])
            ->andReturn(null);
        $userMock->shouldReceive('create')
            ->with([
                'id' => Mockery::type('string'),
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Mockery::type('string'),
            ])
            ->andReturn(new Users([
                'id' => (string) Str::uuid(),
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Crypt::encryptString($data['password']),
            ]));

        $userMock->shouldReceive('save')->andReturn(true);

        $service = new CreateUserService($request);
        $result = $service->toCreate();

        $this->assertTrue($result['status']);
        $this->assertEquals($data['name'], $result['response']['name']);
        $this->assertStringStartsWith('Bearer ', $result['response']['token']);
    }

    
    public function test_user_already_exists()
    {
        $data = [
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => 'password123',
        ];
    
        $request = new Request($data);
    
        $userMock = Mockery::mock(Users::class);
        $this->app->instance(Users::class, $userMock);
    
        $userMock->shouldReceive('where')
            ->with('email', '=', $data['email'])
            ->andReturnSelf();
        $userMock->shouldReceive('first')
            ->andReturn(new Users(['email' => $data['email']]));
    
        $service = new CreateUserService($request);
    
        $result = $service->toCreate();
        $this->assertFalse($result['status']);
        $this->assertEquals('User already exists!', $result['response']);
    }
}
