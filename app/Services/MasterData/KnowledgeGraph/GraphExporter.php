<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph;

use Illuminate\Support\Facades\File;
use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;
/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Exporter
 * ==========================================================================
 *
 * Exports Knowledge Graph into multiple formats.
 *
 * Supported Formats
 * -----------------
 * - PHP
 * - JSON
 *
 * Future
 * ------
 * - GraphViz (DOT)
 * - Mermaid
 * - Neo4j
 * - Gephi
 *
 * ==========================================================================
 */
final class GraphExporter
{
    /**
     * Constructor.
     */
    public function __construct(
        protected string $outputPath = ''
    ) {
        if ($this->outputPath === '') {

            $this->outputPath = config_path(
                'masterdata/knowledge_graph.php'
            );

        }
    }

    /**
     * =========================================================================
     * Export
     * =========================================================================
     *
     * Exports graph into PHP file.
     */
    public function export(
        GraphRepository $repository
    ): string
    {
        $graph = $repository->toArray();

        ksort($graph);

        $content = <<<PHP
<?php

declare(strict_types=1);

return

PHP;

        $content .= var_export(
            $graph,
            true
        );

        $content .= ";\n";

        File::ensureDirectoryExists(
            dirname($this->outputPath)
        );

        File::put(
            $this->outputPath,
            $content
        );

        return $this->outputPath;
    }

    /**
     * =========================================================================
     * Export JSON
     * =========================================================================
     */
    public function exportJson(
        GraphRepository $repository,
        ?string $path = null
    ): string
    {
        $path ??= str_replace(
            '.php',
            '.json',
            $this->outputPath
        );

        File::ensureDirectoryExists(
            dirname($path)
        );

        File::put(

            $path,

            json_encode(

                $repository->toArray(),

                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES

            )

        );

        return $path;
    }

    /**
     * =========================================================================
     * Preview
     * =========================================================================
     *
     * Returns export array without writing file.
     *
     * @return array<string,mixed>
     */
    public function preview(
        GraphRepository $repository
    ): array
    {
        return $repository->toArray();
    }

    /**
     * =========================================================================
     * Output Path
     * =========================================================================
     */
    public function outputPath(): string
    {
        return $this->outputPath;
    }

    /**
     * =========================================================================
     * Set Output Path
     * =========================================================================
     */
    public function setOutputPath(
        string $path
    ): self
    {
        $this->outputPath = $path;

        return $this;
    }
}