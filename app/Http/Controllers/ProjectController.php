<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $query = Project::query();
        $projects = $query->paginate(10);

        return inertia('Dashboard', [
            'projects' => ProjectResource::collection($projects),




        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia("Project/create", [
            'projects' => Project::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

   // app/Http/Controllers/ProjectController.php

   public function store(StoreProjectRequest $request)
   {
       // Validasi dan simpan data proyek baru
       $validated = $request->validated();
       $validated['created_by'] = Auth::id();
       $validated['updated_by'] = Auth::id();

       $project = Project::create($validated);

       // Kirim data proyek baru ke frontend setelah berhasil dibuat
       return redirect()->route('dashboard') // Ganti 'dashboard' dengan nama route yang sesuai
        ->with('success', 'Project berhasil dibuat.');

   }



    /**
     * Display the specified resource.
     */


public function show($id)
{
    // Mengambil project berdasarkan ID
    $project = Project::findOrFail($id);

    // Mengambil boards terkait dengan project_id
    $boards = DB::table('boards')->where('boards.projects_id', '=', $id)->get();


    // Menampilkan tampilan menggunakan Inertia dan mengirim data project serta boards
    return Inertia::render('Projects/index', [
        'project' => $project,
        'boards' => $boards,
    ]);
}



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        // Form untuk mengedit proyek
        return inertia('Project/edit', [
            'project' => new ProjectResource($project),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {



        // Validasi dan update proyek

        $validated = $request->validated();
        $validated['updated_by'] = Auth::id(); // Menambahkan updated_by

        $project->update($validated);





    }

    /**
     * Remove the specified resource from storage.
        */
        public function destroy($id)
        {
            $project = Project::findOrFail($id);

            // Delete related tasks first
            foreach ($project->boards as $board) {
                $board->tasks()->delete();
            }

            // Delete related boards
            $project->boards()->delete();

            // Finally delete the project
            $project->delete();

            return redirect()->route('dashboard')
                ->with('success', 'Project deleted successfully.');
        }

}
