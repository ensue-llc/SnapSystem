<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/22/2017
 * Time: 5:38 PM
 */

namespace Ensue\NicoSystem\Foundation\Database;


use Illuminate\Database\Schema\Blueprint;

class NicoBlueprint extends Blueprint
{
    /**
     * The method that adds editors(created_by, updated_by and deleted_by) to the table
     */
    public function editors()
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
    public function creator(string $columnName = "created_by", string $foreignTable = "users", string $foreignColumn = "id")
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
    protected function editor(string $columnName, string $foreignTable, string $foreignColumn)
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
    public function updater(string $columnName = "updated_by", string $foreignTable = "users", string $foreignColumn = "id")
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
    public function deletor(string $columnName = "deleted_by", string $foreignTable = "users", string $foreignColumn = "id")
    {
        if (!$columnName) {
            $columnName = "created_by";
        }
        $this->editor($columnName, $foreignTable, $foreignColumn);
    }

}
