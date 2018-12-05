<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name'                  => 'Luff1',
            'email'                 => 'luff1@example.com',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ]);

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    public function test_user_can_fully_confirm_their_email_addresses()
    {
        $this->post(route('register'), [
            'name'                  => 'Luff1',
            'email'                 => 'luff1@example.com',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ]);

        $user = User::query()->whereName('Luff1')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $response = $this->get(route('register.confirm', ['token' => $user->confirmation_token]));

        $this->assertTrue($user->fresh()->confirmed);
        $response->assertRedirect('/threads');
    }

    public function test_confirming_an_invalid_token()
    {
        $this->get(route('register.confirm'), ['token' => 'invalid'])
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Unknown token.');
    }
}
