<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
strong {
    font-weight: 600;
}

a {
    color: #039be5;
    text-decoration: none;
}

a:hover {
    color: #006db3;
}

.email {
    max-width: 960px;
    padding: 20px;
    margin: 50px auto;
    font-family: sans-serif;
    line-height: 1.4;
}

.front-matter {
    margin-bottom: 30px;
    padding: 20px 0;
    border-bottom: 1px solid #e6e6e6;
}

.greeting {
    margin-bottom: 10px;
}

.messages {
    box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.2), 0px 1px 1px 0px rgba(0, 0, 0, 0.14), 0px 2px 1px -1px rgba(0, 0, 0, 0.12);
    padding: 20px;
}

.messages-title {
    font-size: 24px;
    font-weight: 300;
    line-height: 1.8;
}

.message {
    border: 1px solid #ddd;
    border-collapse: collapse;
    background: #fafafa;
    padding: 20px;
    margin-bottom: 5px;
    font-size: 18px;
}

.message:nth-child(odd) {
    background: #e1f5fe;
}

.content {
    margin: 0;
    color: #000;
}

.meta {
    font-size: 12px;
    color: #555;
}

.types {
    font-size: 11px;
    padding-top: 5px;
}

.type {
    padding: 3px 5px;
    background: #dfdfdf;
    color: #222;
    display: inline-block;
}


    </style>
  </head>
  <body>
      <div class="email">
          <div class="front-matter">
              <p class="greeting">Hello {{ $to->name }},</p>
              <p>There is a new message on Work Order #<a href="#0">{{ $workOrder->id }}</a>.</p>
          </div>
          <div class="messages">
              <h2 class="messages-title">Messages on #<a href="#0">{{$workOrder->id}}</a></h2>
              @foreach ($notes as $note)
                  <div class="message">
                      <blockquote class="content">
                          <p>{{$note->message}}</p>
                      </blockquote>
                      <p class="meta"><strong>{{$note->user->name}}</strong> on {{$note->created_at}} to {{$note->to->name ?? "System" }}</p>
                      <div class="types">
                          <div class="type">{{$note->fk_note_type}}</div>
                      </div>
                  </div>
              @endforeach

          </div>
      </div>
  </body>
</html>
