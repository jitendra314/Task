<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\project;
use App\Models\task;

class ProjectController extends Controller
{
    public function index(){
        $rand  = rand(000000, 999999); //Gener
        $projects = project::with('tasks')->get()->toArray();
        return view('projects.projects', compact('rand', 'projects'));
    }

    public function create(Request $request)
    {

        $request->validate([
            'projectCode' => 'required|numeric|unique:projects,project_code',
            'projectName' => 'required',
            'tasks.*.task_name' => 'required',
            'tasks.*.task_hours' => 'required|numeric',
        ]);

        // Create new project
        $project = project::create([
            'project_code' => $request->projectCode,
            'project_name' => $request->projectName,
        ]);

        // Create tasks for the project
        foreach ($request->tasks as $taskData) {
            $task = new task($taskData);
            $project->tasks()->save($task);
            if($project){
                $response = ['message' => 'Project updated successfully', 'code'=>200];
            }else{
                $response = ['message' => 'Error occured while updating projects!', 'code'=>201];
            }
        }

        return response()->json($response);
    }

    public function delete($id){
        $project = project::find($id);
        if ($project) {
            $project->delete();
            return response()->json(['message' => 'Project deleted successfully','code'=>200]);
        } else {
            return response()->json(['error' => 'Project not found', 'code'=>201]);
        }
    }

    public function getProjectDetails($id){
        $project = project::with('tasks')->findOrFail($id); // Assuming you have a 'tasks' relationship in your Project model
        return response()->json($project);
    }

    public function updateProject(Request $request, $id)
    {
        // Validate the request data
       $validator =  $request->validate([
            'project_code' => 'required|numeric|unique:projects,project_code,'.$id,
            'project_name' => 'required',
            'tasks.*.task_name' => 'required',
            'tasks.*.task_hours' => 'required|numeric',
        ]);

         // Check if validation fails
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422); // Return validation errors with status code 422
        // }
        // Find the project by ID
        $project = project::findOrFail($id);

        // Update project details
        $project->update([
            'project_code' => $request->project_code,
            'project_name' => $request->project_name,
        ]);

        // Update or create tasks
        if(!empty($request->tasks)){
            foreach ($request->tasks as $taskId => $taskData) {

                $task = task::find($taskData['id']);
                if ($task) {
                    // Task exists, update it
                    $task->update([
                        'project_id' => $project->id, // Set the project_id
                        'task_name' => $taskData['task_name'],
                        'task_hours' => $taskData['task_hours'],
                    ]);
                    if($task){
                        $response = ['message' => 'Project updated successfully', 'code'=>200];
                    }else{
                        $response = ['message' => 'Error occured while updating projects!', 'code'=>201];
                    }
                } else {
                    // Task doesn't exist, create it
                    $projectId = $project->id;
                    $task = task::create([
                        'project_id' => $projectId, // Set the project_id
                        'task_name' => $taskData['task_name'],
                        'task_hours' => $taskData['task_hours'],
                    ]);
                    if($task){
                        $response = ['message' => 'Project updated successfully', 'code'=>200];
                    }else{
                        $response = ['message' => 'Error occured while updating projects!', 'code'=>201];
                    }
                }

            }
        }else{
            $response = ['message' => 'Project updated successfully', 'code'=>200];
        }

        return response()->json($response);
    }

}
