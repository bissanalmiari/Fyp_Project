<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Unipath – Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">
  @yield('style')
</head>

<body>
  @yield('content')
    
   @yield('script')
</body>
</html>