<?php

declare(strict_types=1);

namespace App\Services\MasterData\Generator;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use SplFileInfo;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema File Scanner
 * ==========================================================================
 *
 * Scans every Master Data definition file located inside:
 *
 *      config/masterdata
 *
 * Responsibilities
 * ----------------
 * - Discover Master Data files
 * - Ignore configuration files
 * - Normalize ordering
 * - Return Collection<SplFileInfo>
 *
 * This class performs NO validation.
 * This class performs NO schema analysis.
 *
 * Used by:
 *
 * MasterDataSchemaGenerator
 *
 * ==========================================================================
 */
class SchemaFileScanner
{
    /**
     * Master Data root directory.
     */
    protected string $basePath;

    /**
     * Files that should never be scanned.
     *
     * @var array<int,string>
     */
    protected array $ignoredFiles = [

    'schemas.php',

    'schemas.generated.php',

    'aliases.php',

    'knowledge_graph.php',

    'knowledge_graph.backup.php',

];

    /**
     * Constructor.
     */
    public function __construct(
        ?string $basePath = null
    ) {
        $this->basePath = $basePath
            ?? config_path('masterdata');
    }

    /**
     * =========================================================================
     * Scan
     * =========================================================================
     *
     * Scans every master data definition file.
     *
     * @return Collection<int,SplFileInfo>
     */
    public function scan(): Collection
    {
        if (! File::isDirectory($this->basePath)) {
            return collect();
        }

        return collect(
            File::allFiles($this->basePath)
        )
        ->filter(
            fn (SplFileInfo $file)
                => $this->shouldInclude($file)
        )
        ->sortBy(
            fn (SplFileInfo $file)
                => $this->relativePath($file)
        )
        ->values();
    }

    /**
     * =========================================================================
     * Should Include
     * =========================================================================
     */
    protected function shouldInclude(
        SplFileInfo $file
    ): bool
    {
        if ($file->getExtension() !== 'php') {
            return false;
        }

        return ! in_array(
            $file->getFilename(),
            $this->ignoredFiles,
            true
        );
    }

    /**
     * =========================================================================
     * Relative Path
     * =========================================================================
     *
     * Returns normalized relative path.
     */
    public function relativePath(
        SplFileInfo $file
    ): string
    {
        $relative = str_replace(
            $this->basePath . DIRECTORY_SEPARATOR,
            '',
            $file->getRealPath()
        );

        return str_replace(
            '\\',
            '/',
            $relative
        );
    }

    /**
     * =========================================================================
     * Base Path
     * =========================================================================
     */
    public function basePath(): string
    {
        return $this->basePath;
    }

    /**
     * =========================================================================
     * Ignored Files
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function ignoredFiles(): array
    {
        return $this->ignoredFiles;
    }
   
}