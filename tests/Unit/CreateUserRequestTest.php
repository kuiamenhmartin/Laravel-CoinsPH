<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Faker\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Http\Requests\User\CreateUserRequest;

class CreateUserRequestTest extends TestCase
{
    use DatabaseMigrations;

    /** @var \App\Http\Requests\User\CreateUserRequest */
    private $rules;

    /** @var \Illuminate\Validation\Validator */
    private $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->validator = app()->get('validator');

        $this->rules = (new CreateUserRequest())->rules();
    }

    public function validationProvider()
    {
        /* WithFaker trait doesn't work in the dataProvider */
        // $faker = Factory::create(Factory::DEFAULT_LOCALE);

        return [
            'request_should_fail_when_no_name_is_provided' => [
                'passed' => true,
                'data' => [
                    'name' => 'Kiams'
                ]
            ],
            'request_should_fail_when_no_email_is_provided' => [
                'passed' => true,
                'data' => [
                    'email' => 'qsarza@wylog.com'
                ]
            ],
            'request_should_fail_when_no_password_is_provided' => [
                'passed' => true,
                'data' => [
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                    'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
                ]
            ],
            'request_should_pass_when_data_is_provided' => [
                'passed' => true,
                'data' => [
                    'name' => 'Kiams',
                    'email' => 'qsarza@wylog.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                    'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
                ]
            ]
        ];
    }

    /**
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function testValidationResultsAsExpected($shouldPass, $mockedRequestData)
    {
        $this->assertEquals(
            $shouldPass,
            $this->validate($mockedRequestData)
        );
    }

    protected function validate($mockedRequestData)
    {
        return $this->validator
            ->make($mockedRequestData, $this->rules)
            ->passes();
    }
}
