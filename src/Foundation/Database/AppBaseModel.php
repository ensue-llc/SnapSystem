<?php

namespace Ensue\NicoSystem\Foundation\Database;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Ensue\NicoSystem\Foundation\Status;

abstract class AppBaseModel extends BaseModel
{
    use SoftDeletes, EditorLogs, HasFactory;

    /**
     * Hidden attributes
     *
     * @var array
     */
    protected $hidden = ['deleted_at', 'created_by', 'updated_by', 'deleted_by', 'pivot'];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
    }

    abstract protected static function newFactory();

    /**
     * Tell what model should the model use while updating/creating/deleting.
     * Example return Auth::user()
     *
     * @return Authenticatable|null
     */
    public function editorProvider(): ?Authenticatable
    {
        return Auth::user();
    }

    /**
     * Check whether the model is editable or not
     *
     * @return boolean
     */
    public function isEditable(): bool
    {
        return $this->getAttribute('status') !== Status::STATUS_SUSPENDED;
    }

    /**
     * @return string
     */
    public function getMorphClass(): string
    {
        return class_basename($this);
    }
}

