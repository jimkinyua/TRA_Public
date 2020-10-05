@extends('dashboard.manage')

@section('dashboard-content')
  <form class="ui form" action="{{ route('portal.post.payment') }}" method="post" enctype="multipart/form-data">
    <h3 class="ui dividing header"> Confirm Bank Payment </h3>

    <div class="ui basic padded segment">
      <table class="ui selectable attached basic table">
        <thead>
          <tr>
            <th>Invoice</th>
            <th>Amount Due</th>
            <th class="right aligned">Amount Receipted</th>
          </tr>
        </thead>
        <tbody>
          @foreach($invoices as $invoice)
            <tr>
              <td> <a href="{{route('application.invoice', [ 'id' => $invoice->id() ])}}">{{$invoice->id()}}</a> </td>
              <td>KSh. &nbsp; {{number_format($invoice->total(),2)}}</td>
              <td class="four wide right aligned">
                <input type="text" name="invoice[{{$invoice->id()}}]"
                        placeholder={{number_format($invoice->receipted(),2)}}
                        style="height: 50%; border-radius: 0;" />
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="required field">
      <label>Date</label>
      <input type="text" name="date" placeholder="Date of Payment">
    </div>
    <div class="required field">
      <label>Amount </label>
      <input type="text" name="amount" placeholder="Amount Paid">
    </div>
    <div class="required required field">
      <label>Payment Method</label>
      <select class="ui fluid search dropdown" name="method">
        <option value="">Payment Method</option>
        <option value="1">Mpesa</option>
        <option value="3">Bank</option>
        <option value="4">LAIFOMS Receipt</option>
      </select>
    </div>
    <div class="field">
      <label>Issuing Bank</label>
      <select class="ui fluid search dropdown" name="bank">
        <option value="">Bank</option>
        @foreach ($banks as $key=>$bank)
          <option value={{$key}} >{{$bank}}</option>
        @endforeach
      </select>
    </div>
    <div class="field">
      <label>Slip Number</label>
      <input type="text" name="slip_number" placeholder="Slip Number">
    </div>
    <button class="ui button" type="submit">Submit</button>
  </form>
@endsection

@section('script')
    <script type="text/javascript">
      $('input[name="date"]').datepick();
    </script>
@endsection
