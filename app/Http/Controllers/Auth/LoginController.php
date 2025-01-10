<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle redirection after login based on user type.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->user_type === 'admin') {
            return redirect()->route('admin.index');
        }

        if ($user->user_type === 'user') {
            return redirect()->route('home.index');
        }

        // Optional: Handle unexpected user types.
        return redirect()->route('home');
    }
}
