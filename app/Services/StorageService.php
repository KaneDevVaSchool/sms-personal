<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageService
{
    public function disk(): string
    {
        return config('filesystems.ops_disk', config('filesystems.default'));
    }

    public function storeContractFile(UploadedFile $file, int $contractId, int $version): string
    {
        $path = $file->storeAs(
            "contracts/{$contractId}",
            Str::uuid().'.'.$file->getClientOriginalExtension(),
            $this->disk(),
        );

        return $path;
    }

    /**
     * @return array{url: string, path: string, headers: array<string, string>}
     */
    public function temporaryUploadUrl(string $path, int $minutes = 15): array
    {
        $disk = Storage::disk($this->disk());

        return [
            'url' => $disk->temporaryUrl($path, now()->addMinutes($minutes)),
            'path' => $path,
            'headers' => [],
        ];
    }
}
