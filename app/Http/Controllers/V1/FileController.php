<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Files\DestroysFileRequest;
use App\Http\Resources\V1\Authentications\FileCollection;
use App\Http\Resources\V1\Authentications\FileResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\V1\Files\DownloadFileRequest;
use App\Http\Requests\V1\Files\UpdateFileRequest;
use App\Http\Requests\V1\Files\UploadFileRequest;
use App\Http\Requests\V1\Files\IndexFileRequest;
use App\Models\File;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin')->only(['destroyTrashed']);

        $this->middleware('permission:download-files')->only(['download']);
        $this->middleware('permission:upload-files')->only(['upload']);
        $this->middleware('permission:view-files')->only(['index', 'show']);
        $this->middleware('permission:update-files')->only(['update']);
        $this->middleware('permission:delete-files')->only(['destroy', 'destroys']);
    }

    public function download(File $file)
    {
        if (!Storage::exists($file->full_path)) {
            return (new FileCollection([]))->additional(
                [
                    'msg' => [
                        'summary' => 'Archivo no encontrado',
                        'detail' => 'Intente de nuevo',
                        'code' => '404'
                    ]
                ]);
        }

        return Storage::download($file->full_path);
    }

    public function upload(UploadFileRequest $request, $model)
    {
        if ($request->file('file')) {
            $this->save($request, $request->file('file'), $model);
        }

        if ($request->file('files')) {
            foreach ($request->file('files') as $file) {
                $this->save($request, $file, $model);
            }
        }

        return (new FileCollection([]))->additional(
            [
                'msg' => [
                    'summary' => 'Archivo(s) subido(s)',
                    'detail' => 'Su petición se procesó correctamente',
                    'code' => '201'
                ]
            ]);
    }

    public function index(IndexFileRequest $request, $model)
    {
        if ($request->has('page') && $request->has('per_page')) {
            $files = $model->files()->paginate($request->input('per_page'));

        } else {
            $files = $model->files()
                ->description($request->input('description'))
                ->name($request->input('name'))
                ->paginate($request->input('per_page'));
        }

        return (new FileCollection($files))->additional(
            [
                'msg' => [
                    'summary' => 'success',
                    'detail' => '',
                    'code' => '200'
                ]
            ]);
    }

    public function show(File $file)
    {
        return (new FileResource($file))->additional(
            [
                'msg' => [
                    'summary' => 'success',
                    'detail' => '',
                    'code' => '200'
                ]
            ]
        );
    }

    public function update(UpdateFileRequest $request, File $file)
    {
        $file->name = $request->input('name');
        $file->description = $request->input('description');
        $file->save();
        return (new FileResource($file))->additional(
            [
                'msg' => [
                    'summary' => 'Archivo actualizado',
                    'detail' => 'El archivo fue actualizado correctamente',
                    'code' => '201'
                ]
            ]);
    }

    public function destroy(File $file)
    {
        try {
            Storage::delete($file->full_path);
            $file->delete();
            return (new FileResource($file))->additional(
                [
                    'msg' => [
                        'summary' => 'Archivo eliminado',
                        'detail' => 'Su petición se procesó correctamente',
                        'code' => '201'
                    ]
                ]
            );
        } catch (\Exception $exception) {
            return (new FileResource(null))->additional(
                [
                    'msg' => [
                        'summary' => 'Surgió un error al eliminar',
                        'detail' => 'Intente de nuevo',
                        'code' => '500'
                    ]
                ]
            );
        }
    }

    public function destroys(DestroysFileRequest $request)
    {
        foreach ($request->input('ids') as $id) {
            $file = File::find($id);
            if ($file) {
                $file->delete();
                Storage::delete($file->full_path);
            }
        }

        return (new FileCollection([]))
            ->additional(
                [
                    'msg' => [
                        'summary' => 'Archivo(s) eliminado(s)',
                        'detail' => 'Su petición se procesó correctamente',
                        'code' => '201'
                    ]
                ]
            );
    }

    public function destroyTrashed()
    {
        $filesDeleted = File::onlyTrashed()->get();

        foreach ($filesDeleted as $file) {
            if ($file) {
                $file->forceDelete();
                Storage::delete($file->full_path);
            }
        }
        return (new FileCollection($filesDeleted))->additional(
            [
                'msg' => [
                    'summary' => 'Archivo(s) eliminado(s)',
                    'detail' => 'Su petición se procesó correctamente',
                    'code' => '201'
                ]
            ]);
    }

    private function save($request, $file, $model)
    {
        $newFile = new File();
        $newFile->name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFile->description = $request->input('description');
        $newFile->extension = $file->getClientOriginalExtension();
        $newFile->fileable()->associate($model);
        $newFile->save();

        $file->storeAs(
            'files',
            $newFile->full_path,
            'private'
        );

        $newFile->directory = 'files';
        $newFile->save();
    }
}
