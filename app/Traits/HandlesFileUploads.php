<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait HandlesFileUploads
{
    /**
     * Upload a file to public/uploads directory
     * 
     * @param UploadedFile $file
     * @param string $subdirectory e.g., 'ads', 'blog', 'avatars', 'categories'
     * @return string Relative path from public directory (e.g., 'uploads/ads/filename.jpg')
     */
    protected function uploadToPublic(UploadedFile $file, string $subdirectory): string
    {
        // Generate unique filename
        $filename = $this->generateUniqueFilename($file);
        
        // Ensure directory exists
        $uploadPath = public_path("uploads/{$subdirectory}");
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Get the full destination path
        $destinationPath = $uploadPath . DIRECTORY_SEPARATOR . $filename;
        
        // Handle Livewire temporary files - they're already moved to temp location
        // Use getRealPath() or getPathname() to get the actual file path
        $sourcePath = $file->getRealPath() ?: $file->getPathname();
        
        if ($sourcePath && file_exists($sourcePath)) {
            // Copy the file from temporary location to destination
            // This works for both Livewire temp files and regular uploaded files
            if (!@copy($sourcePath, $destinationPath)) {
                $error = error_get_last();
                throw new \RuntimeException(
                    "Could not copy file from {$sourcePath} to {$destinationPath}. " . 
                    ($error ? $error['message'] : 'Unknown error')
                );
            }
            
            // Set proper permissions
            @chmod($destinationPath, 0644);
        } else {
            // Fallback: try using move() method (for regular uploaded files)
            try {
                $file->move($uploadPath, $filename);
            } catch (\Exception $e) {
                throw new \RuntimeException(
                    "Could not move file to {$destinationPath}: " . $e->getMessage()
                );
            }
        }
        
        // Return relative path (for database storage)
        return "uploads/{$subdirectory}/{$filename}";
    }
    
    /**
     * Generate a unique filename
     * 
     * @param UploadedFile $file
     * @return string
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $basename = Str::slug($basename);
        $timestamp = now()->timestamp;
        $random = Str::random(8);
        
        return "{$basename}_{$timestamp}_{$random}.{$extension}";
    }
    
    /**
     * Delete a file from public/uploads directory
     * 
     * @param string $path Relative path from public directory (e.g., 'uploads/ads/filename.jpg')
     * @return bool
     */
    protected function deleteFromPublic(string $path): bool
    {
        // Remove 'storage/' prefix if present (for backward compatibility)
        $path = str_replace('storage/', '', $path);
        
        // Ensure path is within public/uploads
        if (!str_starts_with($path, 'uploads/')) {
            return false;
        }
        
        $fullPath = public_path($path);
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        
        return false;
    }
    
    /**
     * Check if file exists in public/uploads
     * 
     * @param string $path Relative path from public directory
     * @return bool
     */
    protected function fileExistsInPublic(string $path): bool
    {
        // Remove 'storage/' prefix if present
        $path = str_replace('storage/', '', $path);
        
        // Ensure path is within public/uploads
        if (!str_starts_with($path, 'uploads/')) {
            return false;
        }
        
        return file_exists(public_path($path));
    }
}

