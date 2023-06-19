<?php

namespace Ensue\Snap\Event;


class FileUpload
{
    public function __construct(public readonly string $orginalName,
                                public readonly string $hashValue)
    {
    }
}
