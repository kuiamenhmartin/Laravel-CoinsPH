<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

use App\User;

use App\Services\User\CreateUserService;

class UserServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $userService;

    public function setUp(): void
    {
        parent::setUp();

        \Artisan::call('passport:client --name="Laravel Personal Access Client" --no-interaction --personal');

        \Event::fake();

        $this->withoutExceptionHandling();


        $userSignedUpEvent =  resolve('App\Events\UserSignedUpEvent');

        $this->userService = new CreateUserService($userSignedUpEvent);
    }

    public function testData()
    {
        $stack = [
          'name' => 'Kiams',
          'email' => 'qsarza@wylog.com',
          'password' => 'secrets',
          ];

          $this->assertNotEmpty($stack);

        return $stack;
    }

    /**
     * @dataProvider testUserDataProvider
     */
    public function testDuplicateUserRegistration(array $data)
    {
        $this->assertNotEmpty($data);
        $this->assertNotEmpty($data['name']);
        $this->assertNotEmpty($data['email']);
        $this->assertNotEmpty($data['password']);

        // Assert Exception
        $this->expectException(QueryException::class);

        //insert user
        $this->createUser($data);
        //insert again
        $this->createUser($data);
    }

    public function createUser(array $data)
    {
        //Hash user password before saving
        $data['password']= Hash::make($data['password']);

        //We will create activation code to be used for Email Confirmation
        $data['activation_token'] = str_random(60);

        return $this->userService->execute($data);
    }
}
