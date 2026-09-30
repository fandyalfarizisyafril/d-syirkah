<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function __invoke(string $file)
    {
        abort_unless(preg_match('/^[a-zA-Z0-9]{40}\.(jpg|jpeg|png|webp|pdf)$/', $file, $match), 404);
        abort_unless(Storage::disk('local')->exists('cms/'.$file), 404);
        $path = Storage::disk('local')->path('cms/'.$file);
        $headers = ['X-Content-Type-Options' => 'nosniff', 'Cache-Control' => 'public, max-age=86400'];

        return $match[1] === 'pdf' ? response()->download($path, 'datasheet.pdf', $headers) : response()->file($path, $headers);
    }
}
