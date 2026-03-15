<?php

declare(strict_types=1);

namespace App\Services;

use App\Concerns\HandlesMediaOperations;
use App\Models\Media;
use App\Support\Helper\MediaHelper;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MediaLibraryService
{
    use HandlesMediaOperations;

    public function getMediaList(
        ?string $search = null,
        ?string $type = null,
        string $sort = 'created_at',
        string $direction = 'desc',
        int $perPage = 24
    ): array {
        $query = SpatieMedia::query()->latest();

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('file_name', 'like', "%{$search}%")
                    ->orWhere('mime_type', 'like', "%{$search}%");
            });
        }

        // Apply type filter
        if ($type) {
            switch ($type) {
                case 'images':
                    $query->where('mime_type', 'like', 'image/%');
                    break;
                case 'videos':
                    $query->where('mime_type', 'like', 'video/%');
                    break;
                case 'audio':
                    $query->where('mime_type', 'like', 'audio/%');
                    break;
                case 'documents':
                    $query->whereNotIn('mime_type', function ($q) {
                        $q->select('mime_type')
                            ->from('media')
                            ->where('mime_type', 'like', 'image/%')
                            ->orWhere('mime_type', 'like', 'video/%')
                            ->orWhere('mime_type', 'like', 'audio/%');
                    });
                    break;
            }
        }

        // Apply sorting
        if (in_array($sort, ['name', 'size', 'created_at', 'mime_type'])) {
            $query->orderBy($sort, $direction);
        }

        // Paginate results
        $media = $query->paginate($perPage)->withQueryString();

        // Enhance media items with additional information
        // Note: human_readable_size is already provided by Spatie's Media model
        $media->getCollection()->transform(function ($item) {
            $item->setAttribute('file_type_category', $this->getFileTypeCategory($item->mime_type));
            $item->setAttribute('media_icon', $this->getMediaIcon($item->mime_type));

            return $item;
        });

        // Get statistics
        $stats = $this->getMediaStatistics();

        return [
            'media' => $media,
            'stats' => $stats,
        ];
    }

    public function getMediaStatistics(): array
    {
        return [
            'total' => SpatieMedia::count(),
            'images' => SpatieMedia::where('mime_type', 'like', 'image/%')->count(),
            'videos' => SpatieMedia::where('mime_type', 'like', 'video/%')->count(),
            'audio' => SpatieMedia::where('mime_type', 'like', 'audio/%')->count(),
            'documents' => SpatieMedia::whereNotLike('mime_type', 'image/%')
                ->whereNotLike('mime_type', 'video/%')
                ->whereNotLike('mime_type', 'audio/%')
                ->count(),
            'total_size' => $this->formatFileSize((int) SpatieMedia::sum('size')),
        ];
    }

    public function uploadMedia(array $files): array
    {
        $uploadedFiles = [];

        foreach ($files as $file) {
            // Skip files that don't pass security checks
            if (! $this->isSecureFile($file)) {
                continue;
            }

            // Check demo mode restrictions.
            if (config('app.demo_mode', false)) {
                $mimeType = $file->getMimeType();
                if (! MediaHelper::isAllowedInDemoMode($mimeType)) {
                    throw new \InvalidArgumentException(__('In demo mode, only images, videos, PDFs, and documents are allowed. File type :type is not permitted.', ['type' => $mimeType]));
                }
            }

            // Generate a secure filename
            $safeFileName = $this->generateUniqueFilename($file->getClientOriginalName());
            
            // Get disk from config
            $disk = config('media-library.disk_name', 'public');
            
            // Generate UUID for media
            $uuid = Str::uuid();
            
            // Create media record first to get ID
            $mediaItem = SpatieMedia::create([
                'model_type' => '', 
                'model_id' => 0,
                'uuid' => $uuid,
                'collection_name' => 'uploads',
                'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'file_name' => $safeFileName,
                'mime_type' => $file->getMimeType(),
                'disk' => $disk,
                'conversions_disk' => $disk,
                'size' => $file->getSize(),
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => null,
            ]);
            
            // Now upload file to R2 with proper path: {media_id}/{filename}
            $storagePath = $mediaItem->id . '/' . $safeFileName;
            Storage::disk($disk)->put($storagePath, file_get_contents($file->getRealPath()));

            $uploadedFiles[] = $mediaItem;
        }

        return $uploadedFiles;
    }

    public function deleteMedia(int $id): bool
    {
        $media = Media::findOrFail($id);

        // Delete the physical file - construct path manually to avoid Spatie method issues
        $filePath = 'media/' . $media->file_name;

        if (Storage::disk($media->disk)->exists($filePath)) {
            Storage::disk($media->disk)->delete($filePath);
        }

        return $media->delete();
    }

    public function bulkDeleteMedia(array $ids): int
    {
        $deleteCount = 0;
        $media = Media::whereIn('id', $ids)->get();

        foreach ($media as $item) {
            // Delete the physical file - construct path manually to avoid Spatie method issues
            $filePath = 'media/' . $item->file_name;

            if (Storage::disk($item->disk)->exists($filePath)) {
                Storage::disk($item->disk)->delete($filePath);
            }

            if ($item->delete()) {
                $deleteCount++;
            }
        }

        return $deleteCount;
    }

    public function uploadFromRequest(
        HasMedia $model,
        Request $request,
        string $fieldName,
        string $collection = 'default'
    ): ?SpatieMedia {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);

            // Security checks
            if ($file && $this->isSecureFile($file)) {
                // Check demo mode restrictions
                if (config('app.demo_mode', false)) {
                    $mimeType = $file->getMimeType();
                    if (! MediaHelper::isAllowedInDemoMode($mimeType)) {
                        throw new \InvalidArgumentException(__('In demo mode, only images, videos, PDFs, and documents are allowed. File type :type is not permitted.', ['type' => $mimeType]));
                    }
                }

                return $model->addMedia($file)
                    ->sanitizingFileName(function ($fileName) {
                        return $this->sanitizeFilename($fileName);
                    })
                    ->toMediaCollection($collection);
            }
        }

        return null;
    }

    public function uploadMultipleFromRequest(
        HasMedia $model,
        Request $request,
        string $requestKey,
        string $collection = 'default'
    ): void {
        if ($request->hasFile($requestKey)) {
            foreach ($request->file($requestKey) as $file) {
                // Security checks
                if ($this->isSecureFile($file)) {
                    // Check demo mode restrictions
                    if (config('app.demo_mode', false)) {
                        $mimeType = $file->getMimeType();
                        if (! MediaHelper::isAllowedInDemoMode($mimeType)) {
                            throw new \InvalidArgumentException(__('In demo mode, only images, videos, PDFs, and documents are allowed. File type :type is not permitted.', ['type' => $mimeType]));
                        }
                    }

                    $model->addMedia($file)
                        ->sanitizingFileName(function ($fileName) {
                            return $this->sanitizeFilename($fileName);
                        })
                        ->toMediaCollection($collection);
                }
            }
        }
    }

    public function clearMediaCollection(HasMedia $model, string $collection = 'default'): void
    {
        $model->clearMediaCollection($collection);
    }

    /**
     * Associate existing media with a model by URL or ID
     */
    public function associateExistingMedia(
            HasMedia $model,
            string $mediaUrlOrId,
            string $collection = 'default'
        ): ?SpatieMedia {
            $media = null;

            // Try to find media by ID first (most reliable)
            if (is_numeric($mediaUrlOrId)) {
                $media = SpatieMedia::find($mediaUrlOrId);
            } else {
                $actualUrl = $mediaUrlOrId;

                // Check if it's a proxy URL
                if (str_contains($mediaUrlOrId, '/cdn-proxy?url=')) {
                    // Extract the actual CDN URL from proxy
                    parse_str(parse_url($mediaUrlOrId, PHP_URL_QUERY), $queryParams);
                    if (isset($queryParams['url'])) {
                        $actualUrl = urldecode($queryParams['url']);
                        Log::info('Extracted CDN URL from proxy:', ['url' => $actualUrl]);
                    }
                }

                // Extract media ID from URL path
                $urlPath = parse_url($actualUrl, PHP_URL_PATH);
                $pathParts = explode('/', trim($urlPath, '/'));

                Log::info('URL path parts:', ['parts' => $pathParts]);

                // Try to get media ID from path (first numeric part)
                $mediaId = null;
                foreach ($pathParts as $part) {
                    if (is_numeric($part)) {
                        $mediaId = (int)$part;
                        Log::info('Found media ID in path:', ['id' => $mediaId]);
                        break;
                    }
                }

                if ($mediaId) {
                    $media = SpatieMedia::find($mediaId);
                    if ($media) {
                        Log::info('Media found by ID:', ['id' => $mediaId, 'file' => $media->file_name]);
                    }
                }

                // Fallback: Try to find by filename
                if (!$media) {
                    $fileName = basename($urlPath);
                    $media = SpatieMedia::where('file_name', $fileName)->first();
                }
            }

            if (! $media) {
                Log::warning("Media not found for ID/URL: {$mediaUrlOrId}");
                return null;
            }

            // For R2 media, just update the relationship
            $disk = $media->disk ?? 'public';

            if ($disk === 'r2') {
                try {
                    $media->model_type = get_class($model);
                    $media->model_id = $model->getKey();
                    $media->collection_name = $collection;
                    $media->save();

                    Log::info('R2 media associated:', ['media_id' => $media->id, 'model_id' => $model->getKey()]);

                    return $media;
                } catch (\Exception $e) {
                    Log::error('Failed to associate R2 media: ' . $e->getMessage());
                    return null;
                }
            }

            // For local storage, copy the file
            try {
                $possiblePaths = [
                    storage_path('app/public/' . $media->id . '/' . $media->file_name),
                    storage_path('app/public/media/' . $media->file_name),
                ];

                $mediaPath = null;
                foreach ($possiblePaths as $path) {
                    if (file_exists($path)) {
                        $mediaPath = $path;
                        break;
                    }
                }

                if ($mediaPath) {
                    $copiedMedia = $model
                        ->addMedia($mediaPath)
                        ->preservingOriginal()
                        ->usingName($media->name)
                        ->usingFileName($media->file_name)
                        ->toMediaCollection($collection);

                    return $copiedMedia;
                }
            } catch (\Exception $e) {
                Log::error('Failed to associate media: ' . $e->getMessage());
            }

            return null;
        }
}
