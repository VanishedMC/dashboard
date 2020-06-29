<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Stupidmeme</title>
  <meta name="twitter:card" content="photo" />
  <meta name="twitter:description" content="Stupidmeme" />
  <meta name="twitter:image:src" content="{{$image_file}}" />
  <meta property="og:image" content="{{$image_file}}" />
  <meta property="og:url" content="{{$url}}" />
  <meta property="og:type" content="website" />
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
    <img src="{{$image_file}}" alt="">
  </div>
</body>

</html>