@props(['eyebrow', 'title', 'description' => ''])
<section class="page-heading"><div class="container"><p class="eyebrow">{{ $eyebrow }}</p><h1>{{ $title }}</h1>@if($description)<p class="lead">{{ $description }}</p>@endif</div></section>
