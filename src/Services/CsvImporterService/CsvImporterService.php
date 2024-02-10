<?php

namespace Services\CsvImporterService;

use Illuminate\Support\Facades\Log;
use League\Csv\Exception;
use League\Csv\Reader;
use Services\CsvImporterService\Exceptions\CsvImporterServiceException;

class CsvImporterService
{
    /**
     * @param array $record
     * @return array
     */
    private function clean(array $record): array
    {
        foreach ($record as $key => $value) {
            $value = utf8_encode($value);
            $value = trim($value);
            $value = $value === '' ? null : $value;

            $record[$key] = $value;
        }

        return $record;
    }


    /**
     * @param string $path
     * @return int
     */
    public function linesInFile(string $path): int
    {
        return (int)trim(exec("wc -l $path"));
    }


    /**
     * @param string $path
     * @param array $expectedHeaders
     * @throws CsvImporterServiceException
     */
    public function validateHeaders(string $path, array $expectedHeaders)
    {
        $headersFromFile = trim(shell_exec("head -1 $path"));
        $expectedHeaders = implode(',', $expectedHeaders);

        if ($expectedHeaders !== $headersFromFile) {
            Log::error('Expected: ' . $expectedHeaders);
            Log::error('Actual: ' . $headersFromFile);

            throw new CsvImporterServiceException("Headers did not match. Check the logs.");
        }
    }

    /**
     * @param string $path
     * @param callable $callback
     * @throws CsvImporterServiceException
     */
    public function import(string $path, callable $callback)
    {
        try {
            $csv = Reader::createFromPath($path, 'r');
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();

            foreach ($records as $record) {
                $record = $this->clean($record);
                $callback($record);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new CsvImporterServiceException($e->getMessage());
        }
    }
}
