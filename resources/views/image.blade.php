<!DOCTYPE html>
<html lang="en">

<head>
  <title>{{ isset($title) ? $title : 'Stupidmeme'}}</title>

  <meta charset="UTF-8">
  <meta name="twitter:card" content="photo" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="{{ $url }}" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta property="og:image" content="{{ $image_url }}" />
  <meta name="twitter:description" content="Stupidmeme" />
  <meta name="twitter:image:src" content="{{ $image_url }}" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    body {
      background-color: #383838;
    }

    .center {
      margin: auto;
      width: 80%;
      padding: 10px;
      text-align: center;
    }

    img {
      max-width: 100%;
      border: 2px solid white;
    }

    p {
      color: white;
      font-family: arial;
    }
  </style>
</head>

<body>
  <div class="center">
    <img src="{{ $image_url }}" alt="">
  </div>
</body>

</html>