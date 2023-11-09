<?php

namespace App\Services;

use App\Contracts\FileableInterface;
use App\Models\File;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    public function verificarInterface(Model|FileableInterface $model)
    {
        if (!in_array('App\Contracts\FileableInterface', class_implements($model))) {
            throw new Exception('O model precisa implementar a interface "FileableInterface".');
        }
    }

    public function storeFile(array|UploadedFile $files, Model|FileableInterface $model, $directoryName = null)
    {
        $this->verificarInterface($model);

        $modelName = Str::lower(class_basename($model));

        $erro = false;
        $files = is_array($files) ? $files : array($files);
        
        foreach ($files as $file) {
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('public/uploads/files/' . $modelName . $directoryName, $fileName);

            if (!$path) {
                $erro = true;
                continue;
            }
            
            $model->file()->create(['path' => $path]);
        }
        if ($erro) {
            throw new Exception('Não foi possível fazer upload de um ou mais arquivos.');
        }
    }

    public function updateFile(UploadedFile $file, File $fileInfo)
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        
        $path = $file->storeAs(pathinfo($fileInfo->path)['dirname'], $fileName);
        
        if (!$path) {
            throw new Exception('Não foi possível fazer upload do arquivo.');
        }
        
        Storage::delete($fileInfo->path);
        $fileInfo->update(['path' => $path]);
    }

    
    public function getFile(File $fileInfo)
    {
        $file = Storage::get($fileInfo->path);
        $mimeType = Storage::mimeType($fileInfo->path);
        
        return compact('file', 'mimeType');
    }
    
    public function deleteFile(File $fileInfo)
    {
        Storage::delete($fileInfo->path);
        $fileInfo->delete();
    }

    public function deleteAllFiles(Model $model)
    {
        $this->verificarInterface($model);

        foreach ($model->file as $file) {
            $this->deleteFile($file);
        }
    }
}
