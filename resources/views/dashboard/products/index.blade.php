@extends('layouts.dashboard')

@section('content')
<h1>商品管理</h1>
<form method="GET" action="{{ route('dashboard.products.index')}}" class="form-inline">
    並び替え
    <select name="sort" onChange="this.form.submit();" class="form-inline ml-2">
        @foreach ($sort as $key => $value)
        @if ($sorted == $value)
        <option value=" {{ $value}}" selected>{{ $key }}</option>
        @else
        <option value=" {{ $value}}">{{ $key }}</option>
        @endif
        @endforeach
    </select>
</form>

<div class="w-75 mt-2">
    <div class="w-75">
        <form method="GET" action="{{ route('dashboard.products.index') }}">
            <div class="d-flex flex-inline form-group">
                <div class="d-flex align-items-center">
                    商品ID・商品名
                </div>
                <textarea id="search-products" name="keyword" class="form-controll ml-2 w-50">{{$keyword}}</textarea>
            </div>
            <button type="submit" class="btn samazon-submit-button">検索</button>
        </form>
    </div>

    <div class="d-flex justify-content-between w-75 mt-4">
        <h3>合計{{$total_count}}件</h3>

        <a href="{{ route('dashboard.products.create') }}" class="btn samazon-submit-button">+ 新規作成</a>
    </div>
    <div class="table-responsive">
        <table class="table mt-5">

            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">画像</th>
                    <th scope="col">商品名</th>
                    <th scope="col">価格</th>
                    <th scope="col">カテゴリ名</th>
                    <th scope="col">親カテゴリ名</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <th scope="row">{{ $product->id }}</td>
                    <td>
                    @if ($product->image !== "")
                    <img src="{{ asset('storage/products/'.$product->image) }}" class="h-10 img-fluid">
                    @else
                    <img src="{{ asset('img/dummy.png')}}" class="h-10 img-fuild">
                    @endif
                    </td>
                <td class="align-middle">{{ $product->name }}</td>
                <td class="align-middle">{{ $product->price }}</td>
                <td class="align-middle">{{ $product->category["name"] }}</td>
                <td class="align-middle">{{ $product->category["major_category_name"] }}</td>
                <td class="align-middle">
                    <a href="/dashboard/products/{{ $product->id }}/edit" class="dashboard-edit-link">編集</a>
                </td>
                <td class="align-middle">
                    <form action="/dashboard/products/{{ $product->id }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn dashboard-delete-link">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $products->links() }}
</div>
@endsection
<style>
    table{
        table-layout: fixed;
    }
</style>