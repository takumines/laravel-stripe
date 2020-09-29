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
        <div class="col-6 text-center">
            <a class="btn btn-lg btn-success" href="{{ route('stripe') }}">購入画面</a>
        </div>
        <div class="col-6 text-center">
            <a class="btn btn-lg btn-info" href="{{ route('chargeView') }}">単発決済画面</a>
        </div>
    </div>
    <div class="mt-5 row justify-content-center">
        サブスクリプション状態
        <table class="mt-2 table">
            <tr><th>サブスク状態</th><th>プラン名</th><th>登録カード</th></tr>
            @if($status === 'cancelled' || $status === 'unsubscribed')
                <tr><td>登録なし</td></tr>
            @else
                @if($status === 'trial')
                    <tr><td>{{ $status }}</td><td>{{ $details['plan'] }}</td><td>{{ $details['card_last_four'] }}</td><td>{{ $details['trial_end_date']}}</td></tr>
                @else
                    <tr><td>{{ $status }}</td><td>{{ $details['plan'] }}</td><td>{{ $details['card_last_four'] }}</td></tr>
                @endif
            @endif
        </table>
    </div>
    <div class="row">
        <div class="col-6 text-center">
            <a class="btn btn-lg btn-primary" href="#">プラン変更</a>
        </div>
        <div class="col-6 text-center">
            <a class="btn btn-lg btn-danger" href="{{ route('stripeCancel') }}">キャンセル</a>
        </div>
    </div>
    <h3 class="text-center">お支払情報</h3>
    <div class="mt-5 row justify-content-center">
        <table class="mt-2 table">
            <tr><th>請求日</th><th>内容</th><th>契約期間</th><th>請求額</th></tr>

            @foreach ($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->date()->format('Y年m月d日') }}</td>
                    <td>{{ $invoice->plan_name }}</td>
                    <td>{{ $invoice->date()->format('Y年m月d日') . ' ~ ' . $invoice->stopped}}</td>
                    <td>{{ $invoice->total() }}</td>

                    <td><a href="{{ $invoice->hosted_invoice_url }}">請求書</a></td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection
