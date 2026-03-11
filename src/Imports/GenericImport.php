<?php

namespace DanDelvo\ExcelImportForBackpack\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class GenericImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
    protected $modelClass;
    protected $mapping;

    public function __construct($model, $mapping)
    {
        $this->modelClass = get_class($model);
        $this->mapping = $mapping; 
    }

    public function model(array $row)
    {
        $data = [];
        
     
        foreach ($this->mapping as $excelHeader => $dbColumn) {
            if (!empty($dbColumn)) {
                $formattedHeader = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $excelHeader));
                $data[$dbColumn] = $row[$formattedHeader] ?? null;
            }
        }

        return new $this->modelClass($data);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}