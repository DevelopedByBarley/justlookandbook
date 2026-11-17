<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc>{{ route('home') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    @foreach($programs as $program)
    <url>
        <loc>{{ route('program.show', $program->slug) }}</loc>
        <lastmod>{{ $program->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

</urlset>
