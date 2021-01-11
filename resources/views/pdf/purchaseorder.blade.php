@extends('pdf.main')

@section('title', 'Purchase Order ' . $purchaseOrder->id)

@section('contact')
  <h3>Vendor</h3>
    {{ $vendor->company_name }}
  <address>
    <p>{{ $vendor->address }}</p>
    <p>{{ $vendor->address2 }}</p>
    <p>{{ $vendor->city }}, {{ $vendor->state }} {{ $vendor->zip }}</p>
  </address>
  <div>
    <p><strong>Name:</strong> {{ $vendor->rep }}</p>
    <p><strong>Email:</strong> {{ $vendor->rep_email }}</p>
    <p><strong>Phone:</strong> {{ $vendor->rep_phone }}</p>
  </div>
  @parent
  <p>PO #{{ $purchaseOrder->id }}</p>
  <p><strong>Ordered On:</strong> {{ $purchaseOrder->created_at->format('M dS, Y') }}</p>
  <p><strong>Created By:</strong> {{ $purchaseOrder->created_by }}</p>
  <p><strong>Notes:</strong> {{ $purchaseOrder->notes }}</p>
@endsection

@section('content')
  @foreach ($lineItems as $item)
    <div class="line-item">
      <div class="line-item__info grid">
        <div class="col half">
        <h5>MPN</h5>
        <p>{{ $item->product_mpn }}</p>
        </div>
        <div class="col half">
        <h5>Model / SKU</h5>
        <p>{{ $item->product_model }}</p>
        </div>
      </div>
      <div class="line-item__payment grid">
        <div class="col fourth">
          <h5>QTY Ordered</h5>
          <p><span class="digits">{{ number_format($item->qty_ordered) }}</span></p>
        </div>
        <div class="col fourth">
          <h5>QTY Received</h5>
          <p><span class="digits">{{ number_format($item->qty_received) }}</span></p>
        </div>
        <div class="col fourth">
          <h5>Item Cost</h5>
          <p><span class="digits">${{ number_format( $item->invoice_cost, 2) }}</span></p>
        </div>
        <div class="col fourth">
          <h5>Line Total</h5>
          <p><span class="digits">${{ number_format($item->line_total, 2) }}</span></p>
        </div>
      </div>
    </div>
  @endforeach
  <div class="total">
    <p><strong>Total:</strong> ${{ number_format($purchaseOrder->total, 2) }}</p>
  </div>
@endsection
