@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-2">
        @component('components.sidebar', ['categories' => $categories, 'major_category_names' => $major_category_names])
        @endcomponent
    </div>
    <div class="col-9">
        <h1>おすすめ商品</h1>
        <div class="row">
            <div class="col-4">
                <a href="#">
                    <img src="{{ asset('img/orange.png') }}" class="img-thumbnail">
                </a>
                <div class="row">
                    <div class="col-12">
                        <p class="samazon-product-label mt-2">
                            旬のオレンジ詰め合わせ<br>
                            <label>￥2000</label>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <a href="#">
                    <img src="{{ asset('img/pan.png')}}" class="img-thumbnail">
                </a>
                <div class="row">
                    <div class="col-12">
                        <p class="samazon-product-label mt-2">
                            フライパン20cm 鉄製 業務用<br>
                            <label>￥5000</label>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <a href="#">
                    <img src="{{ asset('img/meat.png')}}" class="img-thumbnail">
                </a>
                <div class="row">
                    <div class="col-12">
                        <p class="samazon-product-label mt-2">
                            和牛ロース200g 2枚入り<br>
                            <label>￥8200</label>
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <h1>新着商品</h1>
        <div class="row">
        @foreach ($recently_products as $recently_product)
           <div class="col-3">
               <a href="/products/{{ $recently_product->id }}">
                   <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail">
               </a>
               <div class="row">
                   <div class="col-12">
                       <p class="samazon-product-label mt-2">
                           {{ $recently_product->name }}<br>
                           <label>￥{{ $recently_product->price }}</label>
                       </p>
                   </div>
               </div>
           </div>
        @endforeach
        </div>
    </div>
</div>
@endsection