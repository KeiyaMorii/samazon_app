<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->keyword !== null) {
            $keyword = rtrim($request->keyword);
            if (is_int($request->keyword)) {
                $keyword = (string)$keyword;
            }
            $users = User::where('name', 'like', "%{$keyword}%")
                            ->orwhere('email', 'like', "%{$keyword}%")
                            ->orwhere('address', 'like', "%{$keyword}%")
                            ->orwhere('postal_code', 'like', "%{$keyword}%")
                            ->orwhere('phone', 'like', "%{$keyword}%")
                            ->orwhere('id', "{$keyword}")->paginate(15);
        } else {
            $users = User::paginate(15);
            $keyword = "";
        }
        

        return view('dashboard.users.index', compact('users', 'keyword'));
    }

    public function destroy(User $user)
    {
        if ($user->deleted_flag) {
            $user->deleted_flag = false;
        } else {
            $user->deleted_flag = true;
        }
            
        $user->update();
            
        return redirect()->route('dashboard.users.index');
    }
}
