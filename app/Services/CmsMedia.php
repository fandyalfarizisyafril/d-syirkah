<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CmsMedia
{
    public function save(Model $model, array $data, Request $request, array $fields): void
    {
        $new = [];
        $old = [];
        try {
            foreach ($fields as $field) {
                unset($data[$field], $data['remove_'.$field]);
                if ($request->hasFile($field) || $request->boolean('remove_'.$field)) {
                    $old[] = $model->$field;
                    $data[$field] = null;
                    if ($request->hasFile($field)) {
                        $path = $request->file($field)->store('cms', 'local');
                        if (! $path) {
                            throw new \RuntimeException('Upload tidak dapat disimpan.');
                        }
                        $data[$field] = 'media/'.basename($path);
                        $new[] = $data[$field];
                    }
                }
            }
            $model->fill($data)->save();
        } catch (Throwable $exception) {
            foreach ($new as $path) {
                $this->delete($path);
            }
            throw $exception;
        }
        foreach ($old as $path) {
            $this->delete($path);
        }
    }

    public function delete(?string $path): void
    {
        if ($path && preg_match('#^media/([a-zA-Z0-9]{40}\.(?:jpg|jpeg|png|webp|pdf))$#', $path, $matches)) {
            Storage::disk('local')->delete('cms/'.$matches[1]);
        }
    }
}
