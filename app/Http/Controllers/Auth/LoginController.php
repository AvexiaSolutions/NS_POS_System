<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * ලොගින් පිටුව පෙන්වන අවස්ථාවේදී CSRF Token එක අලුත් කිරීම.
     * මෙය Phone එකෙන් එන 419 Page Expired error එක වළක්වයි.
     */
    public function showLoginForm()
    {
        // පිටුව පෙන්වීමට පෙර දැනට ඇති Session එක අලුත් කරයි
        session()->regenerateToken();
        
        return view('auth.login');
    }

    /**
     * Logout වූ පසු නැවත ලොගින් පිටුවට යොමු කිරීම සහ Cache ඉවත් කිරීම.
     */
    protected function loggedOut(Request $request)
    {
        // Logout වූ පසු පරණ දත්ත බ්‍රවුසර් එකේ ඉතුරු වීම වැළැක්වීමට
        $request->session()->flush();
        return redirect()->route('login');
    }
}
