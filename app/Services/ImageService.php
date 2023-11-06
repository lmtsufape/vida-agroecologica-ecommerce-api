<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function verificarInterface(Model $model)
    {
        if (!in_array('App\Contracts\ImageableInterface', class_implements($model))) {
            throw new Exception('O model precisa implementar a interface "ImageableInterface".');
        }
    }

    public function storeImage(UploadedFile $imagem, Model $model)
    {
        $this->verificarInterface($model);

        $modelName = Str::lower(class_basename($model));
        $nomeImagem = $model->id . '.' . $imagem->getClientOriginalExtension();

        $caminho = $imagem->storeAs('public/uploads/imagens/' . $modelName, $nomeImagem); // O caminho completo é storage/app/public/uploads/imagens/*.

        if (!$caminho) {
            throw new Exception('Não foi possível fazer upload da imagem.');
        }

        $model->imagem()->create(['caminho' => $caminho]);
    }

    public function updateImage(UploadedFile $imagem, Model $model)
    {
        $this->verificarInterface($model);

        $modelName = Str::lower(class_basename($model));
        $nomeImagem = $model->id . '.' . $imagem->getClientOriginalExtension();
        $imagensAntigas = glob(storage_path("app/public/uploads/imagens/{$modelName}/{$model->id}.*"));

        $caminho = $imagem->storeAs('public/uploads/imagens/' . $modelName, $nomeImagem); // O caminho completo é storage/app/public/uploads/imagens/*.

        if (!$caminho) {
            throw new Exception('Não foi possível fazer upload da imagem.');
        }

        $model->imagem()->updateOrCreate(['imageable_id' => $model->id, 'imageable_type' => $modelName], ['caminho' => $caminho]);

        foreach ($imagensAntigas as $arquivo) {
            if (basename($arquivo) != $nomeImagem) {
                File::delete($arquivo);
            }
        }
    }

    public function deleteImage(Model $model)
    {
        $this->verificarInterface($model);

        $imagem = $model->imagem;

        if (!$imagem) {
            throw new Exception('Imagem não encontrada.');
        }

        $imagens = glob(storage_path('app/') . $imagem->caminho);

        File::delete($imagens);
        $imagem->delete();
    }

    public function getImage(Model $model)
    {
        $this->verificarInterface($model);

        $imagem = $model->imagem;

        if (!$imagem || !Storage::exists($imagem->caminho)) {
            throw new Exception('Imagem não encontrada.');
        }

        $file = Storage::get($imagem->caminho);
        $mimeType = Storage::mimeType($imagem->caminho);

        return compact('file', 'mimeType');
    }
}
