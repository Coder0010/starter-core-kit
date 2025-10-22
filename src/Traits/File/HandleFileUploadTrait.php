<?php

namespace MkamelMasoud\StarterCoreKit\Traits\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandleFileUploadTrait
{
    protected function handleFileUpload(UploadedFile|string|null $file, ?string $existingFile = null): ?string
    {
        if ($file instanceof UploadedFile) {
            if ($existingFile !== null && $existingFile !== '' && Storage::disk('public')->exists($existingFile)) {
                Storage::disk('public')->delete($existingFile);
            }

            $uploadPath = Str::of(get_called_class())
                ->afterLast('\\')
                ->replace('Service', '')
                ->plural()
                ->lower();

            $filename = uniqid('', true).'.'.$file->getClientOriginalExtension();

            return Storage::disk('public')->putFileAs($uploadPath, $file, $filename);
        }

        return $existingFile;
    }

    protected function deleteFileIfExists(?string $path): void
    {
        if ($path !== null && $path !== '' && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
