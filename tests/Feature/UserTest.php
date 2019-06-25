<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Validation\ValidationException;

use App\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        \Artisan::call('passport:client --name="Laravel Personal Access Client" --no-interaction --personal');

        \Event::fake();

        $this->withoutExceptionHandling();
    }

    /**
     * input data
     * @return [type] [description]
     */
    public function testData()
    {
        $stack = [
            'name' => 'Kiams',
            'email' => 'qsarza@wylog.com',
            'password' => 'secretss',
            'password_confirmation' => 'secretss',
        ];

        $this->assertNotEmpty($stack);

        return  $stack;
    }

    /**
     * @depends testData
     * A basic test example.
     *
     * @return void
     */
    public function testRegistrationFeature(array $data)
    {
        $response = $this->post('/api/auth/register', array_merge($data, ['name' => 'Kiams']));

        $user = User::where('email', 'qsarza@wylog.com')->first();

        // dd($response);
        $response->assertSessionHasNoErrors('name');
        $this->assertNull($user->email_verified_at);
        $this->assertCount(1, User::all());
        $this->assertSame('Kiams', $user->name);

        $response->assertStatus(200);
    }

    /**
     * @depends testData
     * Check if input data passes validation
     */
    public function testFormRequest(array $requestParams)
    {
        

            //https://medium.com/@daaaan/a-guide-to-unit-testing-laravel-form-requests-in-a-different-way-f1bdb6d86053
                //https://stackoverflow.com/questions/36978147/unit-test-laravels-formrequest
    }
}
