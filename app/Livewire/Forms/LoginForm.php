<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Determine if the input is an email or username
        $loginField = filter_var($this->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'email';
        $credentials = [
            $loginField => $this->email,
            'password' => $this->password
        ];

        // Attempt to retrieve the user first
        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        if (!$user) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        // Check if the user's credentials are valid
        if (!Auth::validate($credentials)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        // Check the user's status
        if ($user->status === 0 || $user->status === 2) {
            RateLimiter::hit($this->throttleKey());
        
            // Check if status is 2 and log out the user
            if ($user->status === 2 && Auth::check()) {
                Auth::logout();
        
                // Optionally invalidate the session
                request()->session()->invalidate();
                request()->session()->regenerateToken();
            }
        
            // Define message based on user status
            $message = $user->status === 0
                ? "Your account is pending approval. Please contact the administrator if you believe this is an error."
                : "Your account has been disabled. Please contact the administrator for assistance.";
        
            throw ValidationException::withMessages([
                'form.email' => $message,
            ]);
        }
        
        // If all checks pass, log the user in
        Auth::login($user, $this->remember);

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}
