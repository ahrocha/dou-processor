<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;

use App\Services\UploadService;
use App\Http\Requests\UploadRequest;

class UploadController extends Controller
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Upload::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UploadRequest $request)
    {
        $newUpload = $this->uploadService->create($request->file('arquivo'));

        return response()->json([
            'mensagem' => 'Arquivo enviado com sucesso.',
            'upload_id' => $newUpload->id,
        ], 201);
    }

    public function edit(Upload $upload)
    {
        // Método edit não é necessário para este desafio, mas pode ser implementado se necessário
        return response()->json([
            'mensagem' => 'Método edit não implementado.',
            'upload_id' => $upload->id,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Upload $upload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Upload $upload)
    {
        //
    }

    public function show(Upload $upload)
    {
        $perPage = request()->query('per_page', 20);
        $artigos = $upload->artigos()
        ->select([
            'id',
            'upload_id',
            'article_id',
            'name',
            'art_type',
            'art_category',
            'pdf_page',
            'pub_date',
            'created_at',
            'updated_at',
        ])
        ->paginate($perPage);

        $artigos->getCollection()->transform(function ($artigo) {
            $artigo->url = route('artigos.show', ['artigo' => $artigo]);
            return $artigo;
        });

        return response()->json([
            'upload' => $upload,
            'artigos' => $artigos,
        ]);
    }
}
