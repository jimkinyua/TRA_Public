@extends('app')

@section('content')
        <div class="invoice">
            <div class="row">
                <div class="span4">
                    <h1 class="invoice-title">INVOICE</h1>
                </div>
                <div class="span4 offset4">
                    {{Api::showLogo('inv-logo text-center',50,50)}}
                    <p>Uasin Gishu County <br/>
                        Code-Address, Town <br/>
                        Phone: +61 3 8376 6284</p>
                </div>
            </div>
            <div class="invoice-address">
                <div class="row">
                    <div class="span5">
                        <h4 class="inv-to">Invoice To</h4>
                        <h2 class="corporate-id">{{$invoice->business}}</h2>
                        <p>
                            {{$invoice->business->PostalCode}} - {{$invoice->business->PostalAddress}}<br>
                            {{$invoice->business->Town}}<br>
                            Phone: {{$invoice->business->Telephone1}},
                            Email : {{$invoice->business->Email}}
                        </p>

                    </div>
                    <div class="span4 offset3">
                        <div class="inv-col"><span>Invoice#</span> {{$invoice->id()}}</div>
                        <div class="inv-col"><span>Invoice Date :</span> {{$invoice->CreatedDate}}</div>
                        <h1 class="t-due">TOTAL</h1>
                        <h2 class="amnt-value">KES. {{$invoice->total()}}</h2>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-invoice">
            <thead>
            <tr>
                <th>#</th>
                <th>Item Description</th>
                <th></th>
                <th class="text-center">Total</th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{$count}}</td>
                <td>
                    <p>{{$item->application->service}}</p>
                </td>
                <td></td>
                <td class="text-center"><strong>{{$item->Amount}}</strong></td>
            </tr>
            @endforeach
            <tr>
                <td>

                </td>
                <td colspan="" class="payment-method">
                    @if ($invoice->balance() > 0)
                    <h4>Payment Method (MPESA)</h4>
                    <ol id="zap_div">
                        <li>Go to <strong>M-PESA</strong> Menu and select <strong>Pay Bill</strong> </li>
                        <li>Enter Business Number: <strong>530100</strong></li>
                        <li>Enter Account Number: <strong> {{$invoice->id()}} </strong></li>
                        <li>Enter Amount:<strong>{{$invoice->total()}}</strong></li>
                        <li>Enter your M-PESA PIN</li>
                        <li>Send & wait for Confirmation then Click the button</li>
                    </ol>
                    @else
                        <div class="text-center">
                            {{Api::stampPaid(null,200,200)}}
                        </div>
                    @endif
                    <br>
                    <div class="row">
                        <div class="">
                            <div class="progress-bar small hide" data-role="progress-bar" id="pb1"></div>

                        </div>
                        @if ($invoice->balance() > 0)
                        <div class="">
                            <button class="button success" id="pb_start_btn">Confirm Payment</button> <span id="feedback"></span>
                        </div>
                        @endif


                    </div>
                    <h3 class="inv-label">Thank you for your business</h3>
                </td>
                <td class="text-right">
                    <p><strong>Grand Total</strong></p>
                    <p><strong>Amount Paid</strong></p>
                    <p><strong>TOTAL DUE (Balance)</strong></p>
                </td>
                <td class="text-center">
                    <p><strong>KES {{number_format($invoice->total(),2)}}</strong></p>
                    <p><strong>KES {{number_format($invoice->paid(),2)}}</strong></p>
                    <p><strong>KES {{number_format($invoice->balance(),2)}}</strong></p>
                </td>
            </tr>

            </tbody>
        </table>
@endsection


@section('page_js')
    <script>
        $(function(){
            var pb = $('#pb1').progressbar();
            var progress = 0;
            $("#pb_start_btn").on('click', function(){
                $('#pb1').removeClass('hide');
                var interval = setInterval(
                        function(){
                            pb.progressbar('value', (++progress));
                            if (progress == 100) window.clearInterval(interval);
                        }, 100);
                $.get('{{route('pay.invoice',$invoice->InvoiceHeaderID)}}',null,function(result){
                    var code = parseInt(result.code);
                    $('#pb1').hide();
                    if (code == 200){
                        $('#feedback').html('<div class="error-notice"> <div class="oaerror success">'+result.message+'</div></div>');
                        setTimeout(function() {
                            location.reload();
                        },2000);
                    }else{
                        $('#feedback').html('<div class="error-notice"> <div class="oaerror error">'+result.message+'</div></div>');
                        setTimeout(function() {
                            location.reload();
                        },2000);
                    }
                });
            });

            $("#pb_reset_btn").on('click', function(){
                progress = 0;
                pb.progressbar('value', progress);
            });
        });



    </script>
@endsection
