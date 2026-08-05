<?php

namespace App\Filesystem;

use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\UnableToCheckExistence;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\UnableToDeleteFile;
use VercelBlobPhp\Client as VercelClient;
use VercelBlobPhp\CommonCreateBlobOptions;
use VercelBlobPhp\ListCommandOptions;
use VercelBlobPhp\ListCommandMode;

class VercelBlobAdapter implements FilesystemAdapter
{
    protected VercelClient $client;
    protected string $storeUrl;

    public function __construct(string $token)
    {
        $this->client = new VercelClient($token);
        
        // Extract the Store ID from the token: vercel_blob_rw_<storeId>_<secret>
        $parts = explode('_', $token);
        $storeId = $parts[3] ?? null;
        
        if ($storeId) {
            $this->storeUrl = "https://{$storeId}.public.blob.vercel-storage.com";
        } else {
            $this->storeUrl = '';
        }
    }

    public function getUrl(string $path): string
    {
        return rtrim($this->storeUrl, '/') . '/' . ltrim($path, '/');
    }

    public function fileExists(string $path): bool
    {
        try {
            $url = $this->getUrl($path);
            $this->client->head($url);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function directoryExists(string $path): bool
    {
        return false;
    }

    public function write(string $path, string $contents, Config $config): void
    {
        try {
            $options = new CommonCreateBlobOptions(
                addRandomSuffix: false,
                contentType: $config->get('mimetype') ?? $config->get('content_type'),
                allowOverwrite: true
            );
            
            $this->client->put($path, $contents, $options);
        } catch (\Exception $e) {
            throw UnableToWriteFile::atLocation($path, $e->getMessage(), $e);
        }
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        $data = stream_get_contents($contents);
        if ($data === false) {
            throw UnableToWriteFile::atLocation($path, 'Could not read from stream');
        }
        $this->write($path, $data, $config);
    }

    public function read(string $path): string
    {
        try {
            $url = $this->getUrl($path);
            $contents = file_get_contents($url);
            if ($contents === false) {
                throw new \Exception("Could not read file from URL: {$url}");
            }
            return $contents;
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage(), $e);
        }
    }

    public function readStream(string $path)
    {
        try {
            $url = $this->getUrl($path);
            $stream = fopen($url, 'r');
            if ($stream === false) {
                throw new \Exception("Could not open stream for URL: {$url}");
            }
            return $stream;
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage(), $e);
        }
    }

    public function delete(string $path): void
    {
        try {
            $url = $this->getUrl($path);
            $this->client->del([$url]);
        } catch (\Exception $e) {
            throw UnableToDeleteFile::atLocation($path, $e->getMessage(), $e);
        }
    }

    public function deleteDirectory(string $path): void
    {
        // Flattened object storage: directory deletion is a no-op
    }

    public function createDirectory(string $path, Config $config): void
    {
        // Flattened object storage: directories are virtual
    }

    public function setVisibility(string $path, string $visibility): void
    {
        // Visibility is managed at the Vercel Blob store level
    }

    public function visibility(string $path): FileAttributes
    {
        return new FileAttributes($path, null, 'public');
    }

    public function mimeType(string $path): FileAttributes
    {
        try {
            $url = $this->getUrl($path);
            $meta = $this->client->head($url);
            $mime = $meta->contentType ?? 'application/octet-stream';
            return new FileAttributes($path, null, null, null, $mime);
        } catch (\Exception $e) {
            return new FileAttributes($path, null, null, null, 'application/octet-stream');
        }
    }

    public function lastModified(string $path): FileAttributes
    {
        try {
            $url = $this->getUrl($path);
            $meta = $this->client->head($url);
            $time = isset($meta->uploadedAt) ? strtotime($meta->uploadedAt) : time();
            return new FileAttributes($path, null, null, $time);
        } catch (\Exception $e) {
            return new FileAttributes($path, null, null, time());
        }
    }

    public function fileSize(string $path): FileAttributes
    {
        try {
            $url = $this->getUrl($path);
            $meta = $this->client->head($url);
            $size = $meta->size ?? 0;
            return new FileAttributes($path, $size);
        } catch (\Exception $e) {
            return new FileAttributes($path, 0);
        }
    }

    public function listContents(string $path, bool $deep): iterable
    {
        try {
            $options = new ListCommandOptions(
                prefix: $path,
                mode: ListCommandMode::EXPANDED
            );
            $result = $this->client->list($options);
            
            $blobs = $result->blobs ?? [];
            
            foreach ($blobs as $blob) {
                $pathname = $blob->pathname ?? '';
                $size = $blob->size ?? 0;
                $uploadedAt = isset($blob->uploadedAt) ? strtotime($blob->uploadedAt) : time();
                
                yield new FileAttributes($pathname, $size, null, $uploadedAt);
            }
        } catch (\Exception $e) {
            return [];
        }
    }

    public function move(string $source, string $destination, Config $config): void
    {
        try {
            $this->copy($source, $destination, $config);
            $this->delete($source);
        } catch (\Exception $e) {
            throw \League\Flysystem\UnableToMoveFile::fromLocationTo($source, $destination, $e);
        }
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        try {
            $sourceUrl = $this->getUrl($source);
            $this->client->copy($sourceUrl, $destination);
        } catch (\Exception $e) {
            throw \League\Flysystem\UnableToCopyFile::fromLocationTo($source, $destination, $e);
        }
    }
}
