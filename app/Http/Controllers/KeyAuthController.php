<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KeyAuthController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('key-auth');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function auth(Request $request): RedirectResponse
    {
        $value = $request->post('key', null);
        if($value && $value == env('SECRET_AUTH_KEY')) {
            setcookie('secret-key', $value, [
                'expires' => time() + 3600, // 1 hour
                'path' => '/',
                'samesite' => 'Strict',
                'secure' => true,
            ]);

            return redirect()->route('key-management.index');
        }

        return redirect()->route('key-auth.index', ['error' => 'bad-key']);
    }
}
