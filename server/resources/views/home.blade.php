@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3 row justify-content-center">
        <div class="col text-center">
            <a class="btn btn-lg btn-success" href="{{ route('card') }}">購入画面</a>
        </div>
    </div>
    <div class="mt-5 row justify-content-center">
        サブスクリプション状態
        <table class="mt-2 table">
            <tr><th>サブスク状態</th><th>プラン名</th><th>登録カード</th></tr>
            @if($status === 'subscribed')
                <tr><td>{{ $status }}</td><td>{{ $details['plan'] }}</td><td>{{ $details['card_last_four'] }}</td></tr>
            @else
                <tr><td>登録なし</td></tr>
            @endif
        </table>
    </div>
    <div class="row">
        <div class="col-6 text-center">
            <a class="btn btn-lg btn-primary" href="#">プラン変更</a>
        </div>
        <div class="col-6 text-center">
            <a class="btn btn-lg btn-danger" href="{{ route('cancel') }}">キャンセル</a>
        </div>
    </div>
</div>
@endsection
