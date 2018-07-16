<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivateController extends Controller
{
    public function activate(Request $req)
    {
        $user = User::where('token', $req->token)->where('email', $req->email)->firstOrFail();

        $user->update([
            'active' => true,
            'token'  => null,
        ]);

        Auth::loginUsingId($user->id);

        return redirect('home')->with('success', 'Your account acivated successfully, Welcome '.$user->name);
    }
}
