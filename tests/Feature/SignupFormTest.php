<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Event;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\Form;
use Tests\TestCase;

class SignupFormTest extends TestCase
{
    public function test_homepage_renders_the_selected_form_with_precognition(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('href="#sign-up-for-updates"', false)
            ->assertSee('signupForm({form: $form(', false)
            ->assertSee('name="first_name"', false)
            ->assertSee('name="last_name"', false)
            ->assertSee('name="organization"', false)
            ->assertSee('type="email"', false)
            ->assertSee('name="email_address"', false)
            ->assertSee('x-model="form.email_address"', false)
            ->assertSee("form.validate('email_address')", false)
            ->assertSee('x-model="form.website"', false);
    }

    public function test_signup_requires_a_valid_email_address(): void
    {
        Event::fake([FormSubmitted::class]);
        $url = Form::find('sign_up_for_updates')->actionUrl();

        $this->postJson($url, [])->assertUnprocessable()->assertJsonValidationErrors('email_address');
        $this->postJson($url, ['email_address' => 'invalid'])
            ->assertUnprocessable()->assertJsonValidationErrors('email_address');

        Event::assertNotDispatched(FormSubmitted::class);
    }

    public function test_precognition_validates_without_submitting(): void
    {
        Event::fake([FormSubmitted::class]);
        $url = Form::find('sign_up_for_updates')->actionUrl();
        $headers = ['Precognition' => 'true', 'Precognition-Validate-Only' => 'email_address'];

        $this->postJson($url, ['email_address' => 'invalid'], $headers)
            ->assertUnprocessable()->assertJsonValidationErrors('email_address');
        $this->postJson($url, ['email_address' => 'test@example.com'], $headers)
            ->assertNoContent()->assertHeader('Precognition-Success', 'true');

        Event::assertNotDispatched(FormSubmitted::class);
    }

    public function test_valid_signup_is_submitted_successfully(): void
    {
        Event::fake([FormSubmitted::class]);
        // Exercise submission without leaving test data in the site's form storage.
        $form = clone Form::find('sign_up_for_updates');
        $form->store(false);
        Form::partialMock()->shouldReceive('find')->with('sign_up_for_updates')->andReturn($form);

        $this->postJson($form->actionUrl(), [
            'first_name' => 'Test',
            'last_name' => 'Subscriber',
            'organization' => 'Test Organization',
            'email_address' => 'test@example.com',
        ])->assertOk()->assertJsonPath('success', true)
            ->assertJsonPath('submission.email_address', 'test@example.com');

        Event::assertDispatched(FormSubmitted::class);
    }
}
