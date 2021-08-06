<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Project;


class AuthorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {
        $author = new Author();
        $author->project()->associate($project);
        $author->names = $request->names;
        $author->email = $request->email;
        $author->age = $request->age;
        $author->phone = $request->phone;
        $author->identification = $request->identification;

        $author->save();

        return response()->json(
            [
                'data' => $author,
                'msg' => [
                    'summary' => 'Creado correctamente',
                    'detail' => 'El proyecto se creo correctamente',
                    'code' => '201'
                ]
            ], 201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @param Author $author
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Author $author)
    {
        $authors = Project::with('authors')->find($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
