<?php

namespace App\Http\Controllers;

use App\User;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

# ユーザーのマイページとユーザー情報を編集するページを作成、編集ページからデータを受け取り、データベースに保存されている値を更新する処理が必要。
# そのため、indexアクションなどの不要なアクションを削除する。
class UserController extends Controller
{
    public function mypage()
    {
        # Auth::user();を使い、ユーザー自身の情報を$userに保存している
        $user = Auth::user();

        # それをビューへ渡し、ビュー側で表示させる
        return view('users.mypage', compact('user'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $user = Auth::user();

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = Auth::user();

        $user->name = $request->input('name') ? $request->input('name') : $user->name;
        $user->email = $request->input('email') ? $request->input('email') : $user->email;
        $user->postal_code = $request->input('postal_code') ? $request->input('postal_code') : $user->postal_code;
        $user->address = $request->input('address') ? $request->input('address') : $user->address;
        $user->phone = $request->input('phone') ? $request->input('phone') : $user->phone;
        $user->update();

        return redirect()->route('mypage');
    }

    # 会員の住所変更を行うページ用
    public function edit_address()
    {
        $user = Auth::user();

        return view('users.edit_address', compact('user'));
    }

    public function edit_password()
    {
        # パスワード変更画面の表示
        return view('users.edit_password');
    }

    public function update_password(Request $request)
    {
        $user = Auth::user();

        # リクエスト内のpasswordとconfirm_passwordが同一のものかどうかを確認する
        if ($request->input('password') == $request->input('confirm_password')) {
            $user->password = bcrypt($request->input('password'));
            $user->update();
        } else {
            return redirect()->route('mypage.edit_password');
        }

        return redirect()->route('mypage');
    }

    public function favorite()
    {
        $user = Auth::user();

        # ユーザーがお気に入りした商品を全て$favoritesへと保存する
        $favorites = $user->favorites(Product::class)->get();

        return view('users.favorite', compact('favorites'));
    }

    public function destroy(Request $request)
   {
       $user = Auth::user();
        
       if ($user->deleted_flag) {
           $user->deleted_flag = false;
       } else {
           $user->deleted_flag = true;
       }

       $user->update();

       Auth::logout();

       return redirect('/');
   }
}
