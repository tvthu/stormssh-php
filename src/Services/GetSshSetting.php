<?php

namespace tvthu\StormsshPhp\Services;

class GetSshSetting
{
    public function fileGetContents(string $filename)
    {
        return file_get_contents($filename);
    }
}
