@section('content')
<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <title>Shipping Transactions</title>

  <style>
	/* reset.css */
	html {margin:0;padding:0;border:0;}
	body, div, span, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, code, del, dfn, em, img, q, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, dialog, figure, footer, header, hgroup, nav, section {margin:0;padding:0;border:0;font-weight:inherit;font-style:inherit;font-size:100%;font-family:inherit;vertical-align:baseline;}
	article, aside, dialog, figure, footer, header, hgroup, nav, section {display:block;}
	body {line-height:1.5;background:white;}
	table {border-collapse:separate;border-spacing:0;}
	caption, th, td {text-align:left;font-weight:normal;float:none !important;}
	table, th, td {vertical-align:middle;}
	blockquote:before, blockquote:after, q:before, q:after {content:'';}
	blockquote, q {quotes:"" "";}
	a img {border:none;}
	:focus {outline:0;}

	/* typography.css */
	html {font-size:100.01%;}
	body {font-size:75%;color:#222;background:#fff;font-family:"Helvetica Neue", Arial, Helvetica, sans-serif;}
	h1, h2, h3, h4, h5, h6 {font-weight:normal;color:#111;}
	h1 {font-size:3em;line-height:1;margin-bottom:0.5em;}
	h4 {font-size:1.2em;line-height:1.25;margin-bottom:1.25em;}
      h1 {
        margin-top: 35px;
      }
      .container {
        width: 98%;
        margin-left: 1%;
      }
      .headingContainer {
        text-align: center;
        position: relative;
      }
      .tracking_day {
          width: 132px;
          float: left;    
          box-sizing: border-box;
          min-height: 1000px;

      }
      .tracking_day h4 {
          text-align: center;
          background-color: #efefef;
          padding: 1%;
          border-top: 1px solid #ccc;
          border-right: 1px solid #ccc;
          border-left: 1px solid #ccc;
          margin-bottom: 0;
      }
      .tracking_item {
          padding: 6px;
          box-sizing: border-box;
          border: 1px solid #ccc;
          width: 100%;
          color: #000;
      }
      .tracking_item a {
          font-weight: bold;
          color: #204472;
      }
      .tracking_item > div {
          text-align: center;
      }
      .noContact {
        color: #000;
        font-size: 1.1em;
        text-align: center;
      }
      .item_text {
          font-style: italic;
          color: #204472;
          font-size: .8em;
      }
      .days4, .days3 {
          background-color: rgba(255, 255, 0, 0.5);
          color: black;        
      }
      .days2, .days1, .days0 {
          background-color: rgba(255, 128, 0, 0.9);
          color: black;        
      }
      .days-1, .days-2, .days-3, .days-4, .days-5, .days-6, .days-7, .days-8, .days-9, .days-10, .days-11, .days-12, .days-13, .days-14, .days-15, .days-16, .days-17, .days-18, .days-19, .days-20, .days-21, .days-22, .days-23, .days-24, .days-25, .days-26 {
          background-color: rgba(255, 0, 0, 0.9);
          color: #fff;       
      }   
      .days-1 .item_text, .days-2 .item_text, .days-3 .item_text, .days-4 .item_text, .days-5 .item_text, .days-6 .item_text, .days-7 .item_text, .days-8 .item_text, .days-9 .item_text, .days-10 .item_text, .days-11 .item_text, .days-12 .item_text, .days-13 .item_text, .days-14 .item_text, .days-15 .item_text, .days-16 .item_text, .days-17 .item_text, .days-18 .item_text, .days-19 .item_text, .days-20 .item_text, .days-21 .item_text, .days-22 .item_text, .days-23 .item_text, .days-24 .item_text, .days-25 .item_text, .days-26 {
          background-color: rgba(255, 0, 0, 0.9);
          color: #fff;       
      }    
      .days-1 a, .days-2 a, .days-3 a, .days-4 a, .days-5 a, .days-6 a, .days-7 a, .days-8 a, .days-9 a, .days-10 a, .days-11 a, .days-12 a, .days-13 a, .days-14 a, .days-15 a, .days-16 a, .days-17 a, .days-18 a, .days-19 a, .days-20 a, .days-21 a, .days-22 a, .days-23 a, .days-24 a, .days-25 a, .days-26 {
          background-color: rgba(255, 0, 0, 0.9);
          color: #fff;       
      }            
  </style>

	</head>

	<body>
	<div class="container">
	  <div class="headingContainer"><h1>Shipping Tracker</h1>
	    <div style="position: absolute;top:0;right:0;">	
	      <form action="/shipping-transactions" method="POST">  
	      	{{ csrf_field() }}
	      	<a href="/shipping-transactions?no-contact=yes">NO CONTACT</a>&nbsp;&nbsp;&nbsp;&nbsp;
	          <select name="ship_company"><option default="{{ $filter }}">{{ $filter }}</option>
		          @foreach($ship_companies as $ship_company)
		            <option value="{{ $ship_company->ship_company }}">{{ $ship_company->ship_company }}</option>
		          @endforeach
	          </select>&nbsp;&nbsp;
	          <input type="submit" value="Filter">    
   
	      </form>
	    </div>
	  </div>
		<div class="shiptracking"><br>  
		    @foreach ($trackedItems as $daysOut => $items)
		            <div class="tracking_day">  
		            <h4>Days Out {{ $daysOut }}</h4>
		            @foreach ($items as $item) 
		                <div class="tracking_item {{ $item->eta }}">
		                	@if(!$item->customer_contact)
		                		<div class="noContact">NO CONTACT !!</div> 
		                	@endif
		                    Invoice #: <a href="https://wpos.walts.com/pos/review_invoice.php?invoice={{ $item->invoice_num }}" target="_blank"><span class="item_text">{{ $item->invoice_num }}</span></a><br>
	                        Item: <span class="item_text">{{ $item->description }}</span><br>
	                        ETA: <span class="item_text">{{ $item->expected_eta }}</span><br>
	                        Tracking Number: <a href="{{ $item->tracking_url }}" target="_blank"><span class="item_text">{{ $item->bol_tracking }}</span></a><br>
	                        <div><a href="/shipping-transactions/deliver/{{ $item->tid }}">Deliver</a> | <a href="/shipping-transactions/remove/{{ $item->tid }}">Remove</a></div>  
				            @if ($item->scheduled_delivery) 
				              <span class="item_text">Scheduled For: </span>
				              <span class="item_text">{{ $item->scheduled_delivery }}</span><br>
				            @elseif ($item->last_msg_from) 
				              <span class="item_text">Last checked:</span>
                              <span class="item_text">{{ $item->last_msg_from }}</span><br>
                              <span class="item_text">{{ $item->last_msg_time }}</span>            
				            @endif
				        </div> 
		            @endforeach 
		            </div> 
		    @endforeach
		</div>  
	</div>    
	<script>
	     var time = new Date().getTime();
	     function refresh() {
	         if(new Date().getTime() - time >= 300000) 
	             window.location.reload(true);
	         else 
	             setTimeout(refresh, 10000);
	     }
	     setTimeout(refresh, 10000);
	</script>

	</body>
</html>