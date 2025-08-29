<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	 <link rel="preload" href="/dist/css/loading-screen.css" as="style">
 <link rel="preload" href="/dist/css/store.css" as="style">
 <link rel="stylesheet" href="/dist/css/loading-screen.css">
 <link rel="stylesheet" href="/dist/css/store.css">
	<title>{{ app()->bound('label_maintenance_page_title') ? app('label_maintenance_page_title') : 'maintenance' }}</title>
</head>
<body id="body">
<main>
  <div class="container redirect">
      {!! app()->bound('label_maintenance_page_content') ? app('label_maintenance_page_content') : 'maintenance' !!}
  </div>
</main>
</body>
</html>
