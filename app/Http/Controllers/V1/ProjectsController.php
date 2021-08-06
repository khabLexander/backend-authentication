<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // SQL
        // $projects = DB::select('select * from app.projects');

        // QUERY BUILDER
        // $projects = DB::table('app.projects')->get();

        // ELOQUENT
        $projects = Project::get();

        return response()->json(
            [
                'data' => $projects,
                'msg' => [
                    'summary' => 'consulta correcta',
                    'detail' => 'la consulta de proyectos está correcta',
                    'code' => '200'
                ]
            ], 200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // SQL
        /*
        $project = DB::insert('insert into app.projects (code,date,description,approved,title,created_at,updated_at)
                            values (?,?,?,?,?,?,?)', [
            $request->code,
            $request->date,
            $request->description,
            $request->approved,
            $request->title,
            $request->created_at,
            $request->updated_at,
        ]);
        */
        // QUERY BUILDER
        /*
        $project = DB::table('app.projects')->insert([
            'code' => $request->code,
            'date' => $request->date,
            'description' => $request->description,
            'approved' => $request->approved,
            'title' => $request->title,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
        ]);
        */

        //ELOQUENT
        /*
        $project = Project::create([
            'code' => $request->code,
            'date' => $request->date,
            'description' => $request->description,
            'approved' => $request->approved,
            'title' => $request->title,
        ]);
        */

        $project = new Project();
        $project->code = $request->code;
        $project->date = $request->date;
        $project->description = $request->description;
        $project->approved = $request->approved;
        $project->title = $request->title;
        $project->save();

        return response()->json(
            [
                'data' => $project,
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        // SQL
        // $projects = DB::select('select * from app.projects where id = ?', [$project]);

        // QUERY BUILDER
        // $project = DB::table('app.projects')->find($project);

        // ELOQUENT
//        $project = Project::find($project);

        return response()->json(
            [
                'data' => $project,
                'msg' => [
                    'summary' => 'consulta correcta',
                    'detail' => 'la consulta del proyecto se ejecutó correctamente',
                    'code' => '200'
                ]
            ], 200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $project->code = $request->code;
        $project->date = $request->date;
        $project->description = $request->description;
        $project->approved = $request->approved;
        $project->title = $request->title;
        $project->save();

        return response()->json(
            [
                'data' => null,
                'msg' => [
                    'summary' => 'Actualizado correctamente',
                    'detail' => 'EL proyecto se actualizó correctamente',
                    'code' => '201'
                ]
            ], 201
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
//        $project = Project::find($project);
        $project->delete();
        return response()->json(
            [
                'data' => $project,
                'msg' => [
                    'summary' => 'Eliminado correctamente',
                    'detail' => 'EL proyecto se eliminó correctamente',
                    'code' => '201'
                ]
            ], 201
        );
    }

    public function updateState()
    {
        $project = 'aprobado';
        return response()->json(
            [
                'data' => $project,
                'msg' => [
                    'summary' => 'Actualizado correctamente',
                    'detail' => 'EL estado del proyecto se actualizó correctamente',
                    'code' => '201'
                ]
            ], 201
        );
    }

    public function getAuthors()
    {
        $project = ['project1:author1'];
        return response()->json(
            [
                'data' => $project,
                'msg' => [
                    'summary' => 'Actualizado correctamente',
                    'detail' => 'EL estado del proyecto se actualizó correctamente',
                    'code' => '201'
                ]
            ], 201
        );
    }
}
