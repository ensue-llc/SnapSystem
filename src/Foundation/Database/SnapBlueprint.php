<?php

namespace Ensue\Snap\Foundation\Database;


use Illuminate\Database\Schema\Blueprint;

class SnapBlueprint extends Blueprint
{
    /**
     * The method that adds editors(created_by, updated_by and deleted_by) to the table
     */
    public function editors(): void
    {
        $this->creator();
        $this->updater();
        $this->deletor();
    }

    /**
     * @param string $columnName
     * @param string $foreignTable
     * @param string $foreignColumn
     */
    public function creator(string $columnName = "created_by", string $foreignTable = "users", string $foreignColumn = "id"): void
    {
        if (!$columnName) {
            $columnName = "created_by";
        }
        $this->editor($columnName, $foreignTable, $foreignColumn);
    }

    /**
     * @param string $columnName
     * @param string $foreignTable
     * @param string $foreignColumn
     */
    protected function editor(string $columnName, string $foreignTable, string $foreignColumn): void
    {
        $this->unsignedInteger($columnName)->nullable();
        if ($foreignTable && $foreignColumn) {
            $this->foreign($columnName)->references($foreignColumn)->on($foreignTable);
        }
    }

    /**
     * @param string $columnName
     * @param string $foreignTable
     * @param string $foreignColumn
     */
    public function updater(string $columnName = "updated_by", string $foreignTable = "users", string $foreignColumn = "id"): void
    {
        if (!$columnName) {
            $columnName = "created_by";
        }
        $this->editor($columnName, $foreignTable, $foreignColumn);
    }

    /**
     * @param string $columnName
     * @param string $foreignTable
     * @param string $foreignColumn
     */
    public function deletor(string $columnName = "deleted_by", string $foreignTable = "users", string $foreignColumn = "id"): void
    {
        if (!$columnName) {
            $columnName = "created_by";
        }
        $this->editor($columnName, $foreignTable, $foreignColumn);
    }

}
