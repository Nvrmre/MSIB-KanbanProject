<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::latest()->paginate(10);

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
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
        */
    public function destroy($id)
    {

         $project = Project::findOrFail($id);
         $project->delete();


    return redirect()->route('dashboard') // Ganti dengan nama route yang sesuai
        ->with('success', 'Project deleted successfully.');
    }
}
