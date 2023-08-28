<?php

namespace Ensue\Snap\Event;


readonly class FileUpload
{
    public function __construct(public string $originalName,
                                public string $hashValue)
    {
    }
}
