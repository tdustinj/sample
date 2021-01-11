<!DOCTYPE >
<html lang="{{ config('app.locale') }}">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>This PDF</title>
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}">
  </head>
    
  <h1>Walt's TV Purchase Order</h1>
<div class="wrapper">

  <div class="po-meta">
    <address>
      <p>123 Main St.<br />
        Phoenix, AZ 85054</p>
    </address>
    <time>
      <p>May 16th, 2019</p>
    </time>
  </div>
  <h2>Purchase Order {{ $purchaseOrder->id }}</h2>

  @foreach ($lineItems as $item)
    <div class="line-item">
      <div class="line-item__info">
        <p>{{ $item->product_mpn }}</p>
      </div>
      <div class="line-item__payment">
        <p>Item Cost: <span class="digits">${{ $item->invoice_cost }}</span></p>
        <p>Amt of Items: <span class="digits">{{ $item->qty_ordered }}</span></p>
        <p>Line Total: <span class="digits">${{ $item->line_total }}</span></p>
      </div>
    </div>
  @endforeach

  <div class="total">
    <h3>Total</h3>
    ${{ $purchaseOrder->total }}
  </div>
</div>


</body>
</html>
