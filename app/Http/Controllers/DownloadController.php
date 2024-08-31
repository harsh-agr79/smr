<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;
use File;
use Response;

class DownloadController extends Controller
{
    public function downloadFolder()
    {
        $folderPath = public_path(); // Path to the public directory
        $zipFileName = 'public_folder.zip'; // Name of the zip file to be created

        $zip = new ZipArchive;

        // Create a temporary zip file
        $zipFilePath = public_path($zipFileName);
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folderPath));

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($folderPath) + 1);

                    // Add current file to the zip archive
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
        }

        // Return the zip file as a download response
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}

