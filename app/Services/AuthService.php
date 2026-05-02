<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $credentials, string $deviceName = 'web')
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();

        if (!$user instanceof User) {
            throw ValidationException::withMessages([
                'email' => ['Unable to retrieve authenticated user.'],
            ]);
        }
        
        if (!$user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['Your account is deactivated.'],
            ]);
        }

        $user->update(['last_login_at' => now()]);

        return ['user' => $this->formatUser($user)];
    }

    public function logout()
    {
        Auth::logout();
        return true;
    }

    public function getCurrentUser(User $user)
    {
        return $this->formatUser($user);
    }

    protected function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ];
    }
}
