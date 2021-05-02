<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
# indexアクションで使用しているAuth::user()やCart::instance()などを使えるようにするために、以下の2行を追加している
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
# モデルなどを介さずに直接データベースからデータを取得できるようにするためのファイルを読み込んでいる
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # ユーザーのIDを元にこれまで追加しカートの中身を$cart変数に保存している
        $cart = Cart::instance(Auth::user()->id)->content();

        $total = 0;

        foreach ($cart as $c) {
            $total += $c->qty * $c->price;
        }

        return view('carts.index', compact('cart', 'total'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        # ユーザーのIDを元にカートのデータを作成し、add()関数を使って送信されたデータを元に商品を追加している
        Cart::instance(Auth::user()->id)->add(
            [
                'id' => $request->_token,
                'name' => $request->name,
                'qty' => $request->qty,
                'price' => $request->price,
                'weight' => $request->weight,
            ]
        );

        # 商品をカートに追加した後、そのまま商品に個別ページへとリダイレクトさせている
        return redirect()->route('products.show', $request->get('id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        # データベース内のshoppingcartテーブルに保存されているデータを、ユーザーとカートのIDを使用して取得している
        $cart = DB::table('shoppingcart')->where('instance', Auth::user()->id)->where('identifier', $count)->get();

        return view('carts.show', compact('cart'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        # trueの場合は指定した商品をカートから削除している
        if ($request->input('delete')) {
            # Cart::remove()に削除したいカート内のIDを渡すことで、カートから削除できます
            Cart::instance(Auth::user()->id)->remove($request->input('id'));
        } else {
            # falseの場合は商品の個数を$request->input('qty')の値へ保存しています
            Cart::instance(Auth::user()->id)->update($request->input('id'), $request->input('qty'));
        }

        return redirect()->route('carts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user_shoppingcarts = DB::table('shoppingcart')->where('instance', Auth::user()->id)->get();

        # 現在までのユーザーが注文したカートの数を取得している
        $count = $user_shoppingcarts->count();

        # 新しくデータベースに登録するカートのデータ用にカートのIDを一つ増やしている
        $count += 1;
        # ユーザーのIDを使ってカート内の商品情報などをデータベースへと保存している
        Cart::instance(Auth::user()->id)->store;

        # 購入済みのフラグをtrueにして、購入処理を行っている
        # DB::table('shoppingcart')では、データベース内のshoppingcartへのアクセスを行っている。その後where()を使ってユーザーのIDとカート数$countを使い、先程作成したカートのデータを更新している
        DB::table('shoppingcart')->where('instance', Auth::user()->id)->where('number', null)->update(['number' => $count, 'buy_flag' => true]);

        Cart::instance(Auth::user()->id)->destroy();

        return redirect()->route('carts.index');
    }
}
