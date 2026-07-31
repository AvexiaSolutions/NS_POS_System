<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;

class UpdateController extends Controller
{
    private $githubRepo = 'AvexiaSolutions/NS_POS_System';

    private function getCurrentVersion()
    {
        $versionFile = base_path('version.txt');
        if (File::exists($versionFile)) {
            return trim(File::get($versionFile));
        }
        return '1.0.0'; // Fallback
    }

    public function index()
    {
        ini_set('max_execution_time', 300);
        $currentVersion = $this->getCurrentVersion();

        try {
            $url = "https://api.github.com/repos/{$this->githubRepo}/releases/latest";
            
            $response = Http::withHeaders([
                'User-Agent' => 'NS-POS-Update-Client',
                'Accept' => 'application/vnd.github.v3+json',
            ])->withOptions(['verify' => false])->timeout(30)->get($url);
            
            if ($response->successful()) {
                $updateData = $response->json();
                
                $latestVersion = ltrim($updateData['tag_name'], 'v');
                $currentVersionClean = ltrim($currentVersion, 'v');
                
                $hasUpdate = version_compare($latestVersion, $currentVersionClean, '>');

                return view('admin.update.index', [
                    'updateData' => [
                        'version' => $latestVersion,
                        'release_date' => date('Y-m-d H:i', strtotime($updateData['published_at'])),
                        'release_notes' => $updateData['body'] ?? 'No release notes provided.',
                        'download_url' => $updateData['zipball_url']
                    ],
                    'hasUpdate' => $hasUpdate,
                    'currentVersion' => $currentVersion
                ]);
            }

            // If 404, it might mean no releases yet
            if ($response->status() == 404) {
                return view('admin.update.index', [
                    'hasUpdate' => false, 
                    'error' => 'No releases found on the GitHub repository.',
                    'currentVersion' => $currentVersion
                ]);
            }

            throw new \Exception('GitHub API response failed.');

        } catch (\Exception $e) {
            return view('admin.update.index', [
                'hasUpdate' => false, 
                'error' => 'Failed to connect to GitHub: ' . $e->getMessage(),
                'currentVersion' => $currentVersion
            ]);
        }
    }

    public function installUpdate()
    {
        ini_set('max_execution_time', 900);
        set_time_limit(900); 

        try {
            $url = "https://api.github.com/repos/{$this->githubRepo}/releases/latest";
            $response = Http::withHeaders([
                'User-Agent' => 'NS-POS-Update-Client',
                'Accept' => 'application/vnd.github.v3+json',
            ])->withOptions(['verify' => false])->timeout(30)->get($url);
            
            if (!$response->successful()) {
                return back()->with('error', 'Failed to fetch update details from GitHub.');
            }

            $updateData = $response->json();
            
            if (!isset($updateData['zipball_url'])) {
                return back()->with('error', 'Download link not found in GitHub release.');
            }

            if (!File::isDirectory(storage_path('app'))) {
                File::makeDirectory(storage_path('app'), 0775, true);
            }
            
            $zipPath = storage_path('app/temp_update.zip');

            // Download the zip
            Http::withHeaders([
                'User-Agent' => 'NS-POS-Update-Client',
            ])->withOptions([
                'verify' => false,
                'connect_timeout' => 30,
            ])->timeout(900)->sink($zipPath)->get($updateData['zipball_url']);

            if (!File::exists($zipPath) || File::size($zipPath) == 0) {
                if (File::exists($zipPath)) File::delete($zipPath);
                return back()->with('error', 'Failed to download the update file from GitHub.');
            }

            $tempExtractDir = storage_path('app/temp_extract');
            if (File::exists($tempExtractDir)) {
                File::deleteDirectory($tempExtractDir);
            }
            File::makeDirectory($tempExtractDir, 0775, true);

            $zip = new ZipArchive;
            if ($zip->open($zipPath) === TRUE) {
                $extracted = $zip->extractTo($tempExtractDir);
                $zip->close();
                File::delete($zipPath);

                if (!$extracted) {
                    return back()->with('error', 'An error occurred while extracting the files.');
                }

                // GitHub zipball puts contents inside a root folder. Find it.
                $directories = File::directories($tempExtractDir);
                if (count($directories) > 0) {
                    $extractedRoot = $directories[0];
                    File::copyDirectory($extractedRoot, base_path());
                } else {
                    // Fallback if no root folder
                    File::copyDirectory($tempExtractDir, base_path());
                }

                // Cleanup
                File::deleteDirectory($tempExtractDir);

                Artisan::call('optimize:clear');
                
                return back()->with('success', 'System updated successfully! New version: v' . ltrim($updateData['tag_name'], 'v'));
            } else {
                if (File::exists($zipPath)) {
                    File::delete($zipPath);
                }
                return back()->with('error', 'The downloaded update file is corrupted.');
            }

        } catch (\Exception $e) {
            $zipPath = storage_path('app/temp_update.zip');
            if (File::exists($zipPath)) {
                File::delete($zipPath);
            }
            return back()->with('error', 'An error occurred during the update: ' . $e->getMessage());
        }
    }
}
