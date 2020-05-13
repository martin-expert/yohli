@extends('layouts.dashboard_master')
@section('title', 'Transactions History')

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
    
            <div class="content px-4 py-4">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="flex flex-row justify-between mb-6">
                            <h3>
                                <i class="icon-line-awesome-money"></i> 
                                <!-- <img src="{{ asset('assets/images/stripe-logo.png') }}" height="30px"> -->
                            </h3>
                            <h3>Balance: ${{ $account->balance }}</h3>
                        </div>
                            <table class="table-striped table table-hover" id="table1" style="width:100%" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Charges</th>
                                        <th>Status</th>
                                        <th>Type </th>
                                        <th>Payment Method</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($transactions as $t)
                                    <tr>
                                        <td>{{ $t->id }}</td>
                                        <td>${{ $t->amount }}</td>
                                        <td>${{ $t->charge ?? 0 }}</td>
                                        <td>{{ $t->status }}</td>
                                        <td>{{ $t->type }}</td>
                                        <td>{{ $t->payment_method }}</td>
                                        <td>{{ $t->description }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                     
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection