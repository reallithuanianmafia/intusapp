<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function createShortUrl(Request $request)
    {
        $request->validate([
            'original_url' => 'required',
        ]);

        $originalUrl = $request->input('original_url');
        $shortCode = $this->generateShortCode();

        // Function to check for duplicate URL
        $existingUrl = ShortUrl::where('original_url', $originalUrl)->first();

        if ($existingUrl) {
            return response()->json(['short_url' => $existingUrl->short_code]);
        }

        // Google Safe Browsing API
        if ($this->isUrlSafe($originalUrl)) {
            $shortUrl = ShortUrl::create([
                'original_url' => $originalUrl,
                'short_code' => $shortCode,
            ]);

            return response()->json(['short_url' => $shortUrl->short_code]);
        } else {
            return response()->json(['error' => 'The URL is not safe.']);
        }
    }

    public function redirectToOriginalUrl($shortCode)
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->first();

        if ($shortUrl) {
            return redirect($shortUrl->original_url);
        } else {
            abort(404);
        }
    }

    public function checkSafeBrowsing(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $apiKey = config('services.safe_browsing.api_key');
        $url = $request->input('url');

        $response = Http::post('https://safebrowsing.googleapis.com/v4/threatMatches:find?key=' . $apiKey, [
            'client' => [
                'clientId' => 'your-client-id',
                'clientVersion' => '1.0.0',
            ],
            'threatInfo' => [
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'],
                'platformTypes' => ['ANY_PLATFORM'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [
                    ['url' => $url],
                ],
            ],
        ]);

        $body = $response->json();

        if (isset($body['matches']) && count($body['matches']) > 0) {
            // If matches are found, the URL is considered unsafe
            return response()->json(['safe' => false]);
        }

        // If no matches, the URL is considered safe
        return response()->json(['safe' => true]);
    }

    private function generateShortCode()
    {
        $shortCode = Str::random(6);

        while (ShortUrl::where('short_code', $shortCode)->exists()) {
            $shortCode = Str::random(6);
        }

        return $shortCode;
    }

    private function isUrlSafe($url)
    {
        $apiKey = env('SAFE_BROWSING_API_KEY');

        $response = Http::post('https://safebrowsing.googleapis.com/v4/threatMatches:find?key=' . $apiKey, [
            'client' => [
                'clientId' => 'your-client-id',
                'clientVersion' => '1.0.0',
            ],
            'threatInfo' => [
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'],
                'platformTypes' => ['ANY_PLATFORM'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [
                    ['url' => $url],
                ],
            ],
        ]);

        $body = $response->json();

        return !isset($body['matches']) || count($body['matches']) === 0;
    }
}
