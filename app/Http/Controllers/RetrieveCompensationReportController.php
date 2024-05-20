<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RetrieveCompensationReportController extends Controller
{
    /**
     * @param string $filename
     * @return JsonResponse|Response
     */
    public function __invoke(string $filename)
    {
        $path = 'csv/' . $filename;

        try {
            $mimeType = Storage::mimeType($path);
            if (!Storage::exists($path)) {
                throw new FileNotFoundException();
            }

            $content = Storage::get($path);

             Storage::delete($path);

            return response()->make(
                $content,
                200,
                ['Content-Type' => $mimeType]
            );
        }  catch (Exception) {
            return response()->json(['error' => 'Report not found'], 404);
        }
    }

}
