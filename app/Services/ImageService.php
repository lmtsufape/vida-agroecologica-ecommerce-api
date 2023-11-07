<?php

namespace App\Services;

use App\Contracts\ImageableInterface;
use App\Models\Imagem;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function verificarInterface(Model|ImageableInterface $model)
    {
        if (!in_array('App\Contracts\ImageableInterface', class_implements($model))) {
            throw new Exception('O model precisa implementar a interface "ImageableInterface".');
        }
    }

    public function storeImage(array $files, Model|ImageableInterface $model, $directoryName = null)
    {
        $this->verificarInterface($model);

        $modelName = Str::lower(class_basename($model));

        $erro = false;
        foreach ($files as $file) {
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

            $caminho = $file->storeAs('public/uploads/files/' . $modelName . $directoryName, $fileName);

            if (!$caminho) {
                $erro = true;
                continue;
            }
            
            $model->imagem()->create(['caminho' => $caminho]);
        }
        if ($erro) {
            throw new Exception('Não foi possível fazer upload de um ou mais arquivos.');
        }
    }

    public function updateImage(UploadedFile $file, Imagem $fileInfo)
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        
        $caminho = $file->storeAs(pathinfo($fileInfo->caminho)['dirname'], $fileName);
        
        if (!$caminho) {
            throw new Exception('Não foi possível fazer upload do arquivo.');
        }
        
        Storage::delete($fileInfo->caminho);
        $fileInfo->update(['caminho' => $caminho]);
    }

    public function deleteImage(Imagem $fileInfo)
    {
        Storage::delete($fileInfo->caminho);
        $fileInfo->delete();
    }

    public function getImage(Imagem $fileInfo)
    {
        $file = Storage::get($fileInfo->caminho);
        $mimeType = Storage::mimeType($fileInfo->caminho);

        return compact('file', 'mimeType');
    }
}
