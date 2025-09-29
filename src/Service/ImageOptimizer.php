<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageOptimizer
{
    private const MAX_WIDTH = 1200;
    private const MAX_HEIGHT = 800;
    private const QUALITY = 85;
    
    public function optimize(UploadedFile $file, string $targetPath): bool
    {
        try {
            $imageInfo = getimagesize($file->getPathname());
            if (!$imageInfo) {
                return false;
            }
            
            [$width, $height, $type] = $imageInfo;
            
            // Créer l'image source selon le type
            $sourceImage = match($type) {
                IMAGETYPE_JPEG => imagecreatefromjpeg($file->getPathname()),
                IMAGETYPE_PNG => imagecreatefrompng($file->getPathname()),
                IMAGETYPE_WEBP => imagecreatefromwebp($file->getPathname()),
                default => null
            };
            
            if (!$sourceImage) {
                return false;
            }
            
            // Calculer les nouvelles dimensions
            $newDimensions = $this->calculateDimensions($width, $height);
            
            // Créer l'image redimensionnée
            $resizedImage = imagecreatetruecolor($newDimensions['width'], $newDimensions['height']);
            
            // Préserver la transparence pour PNG
            if ($type === IMAGETYPE_PNG) {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
                imagefill($resizedImage, 0, 0, $transparent);
            }
            
            // Redimensionner l'image
            imagecopyresampled(
                $resizedImage, $sourceImage,
                0, 0, 0, 0,
                $newDimensions['width'], $newDimensions['height'],
                $width, $height
            );
            
            // Sauvegarder l'image optimisée
            $success = match($type) {
                IMAGETYPE_JPEG => imagejpeg($resizedImage, $targetPath, self::QUALITY),
                IMAGETYPE_PNG => imagepng($resizedImage, $targetPath, 6), // Compression PNG (0-9)
                IMAGETYPE_WEBP => imagewebp($resizedImage, $targetPath, self::QUALITY),
                default => false
            };
            
            // Libérer la mémoire
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);
            
            return $success;
            
        } catch (\Exception $e) {
            error_log("Erreur lors de l'optimisation de l'image: " . $e->getMessage());
            return false;
        }
    }
    
    private function calculateDimensions(int $originalWidth, int $originalHeight): array
    {
        // Si l'image est déjà plus petite que les dimensions max, on garde les dimensions originales
        if ($originalWidth <= self::MAX_WIDTH && $originalHeight <= self::MAX_HEIGHT) {
            return ['width' => $originalWidth, 'height' => $originalHeight];
        }
        
        // Calculer le ratio pour maintenir les proportions
        $widthRatio = self::MAX_WIDTH / $originalWidth;
        $heightRatio = self::MAX_HEIGHT / $originalHeight;
        
        // Prendre le ratio le plus petit pour que l'image rentre dans les dimensions max
        $ratio = min($widthRatio, $heightRatio);
        
        return [
            'width' => (int) round($originalWidth * $ratio),
            'height' => (int) round($originalHeight * $ratio)
        ];
    }
    
    public function getImageInfo(string $filePath): ?array
    {
        $imageInfo = getimagesize($filePath);
        if (!$imageInfo) {
            return null;
        }
        
        [$width, $height, $type] = $imageInfo;
        $fileSize = filesize($filePath);
        
        return [
            'width' => $width,
            'height' => $height,
            'type' => $type,
            'mime' => $imageInfo['mime'] ?? null,
            'size' => $fileSize,
            'size_formatted' => $this->formatBytes($fileSize)
        ];
    }
    
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
