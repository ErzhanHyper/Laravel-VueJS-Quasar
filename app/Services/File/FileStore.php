<?php

namespace App\Services\File;

class FileStore
{

    public function store($file, $path): string
    {
        $original_name = time() . '_' . $file->getClientOriginalName();
        $filename = "$path/" . $original_name;
        $file->move(public_path('storage/uploads/'.$path), $original_name);
        return $filename;
    }

}
