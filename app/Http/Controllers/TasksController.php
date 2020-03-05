<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
             return view('tasks.index', $data);
        }else{
            return view('tasks.welcome');
        }
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:191',
        ]);

        $task = new Task;
        $task->user_id = \Auth::id();
        $task->status = $request->status;   
        $task->content = $request->content;
        $task->save();

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
            $task = Task::find($id);
            // $taskがログインユーザーのものでなかったら、リダイレクト。
            if (\Auth::id() != $task->user_id) {
                return redirect('/');
            }else{
                return view('tasks.show', [
                   'task' => $task,
                ]);
            }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
            $task = Task::find($id);
            // $taskがログインユーザーのものでなかったら、リダイレクト。
            if (\Auth::id() != $task->user_id) {
                return redirect('/');
            }else{
                 return view('tasks.edit', [
                   'task' => $task,
                ]);
            }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
            $task = Task::find($id);
            // $taskがログインユーザーのものでなかったら、リダイレクト。
            if (\Auth::id() != $task->user_id) {
                return redirect('/');
            }
            
            $this->validate($request, [
                'status' => 'required|max:10',   // 追加
                'content' => 'required|max:191',
            ]);
            
            $task->status = $request->status;  
            $task->content = $request->content;
            $task->save();
            
           
    
           return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
            $task = Task::find($id);
            // $taskがログインユーザーのものでなかったら、リダイレクト。 
            if (\Auth::id() != $task->user_id) {
                return redirect('/');
            }
            
            $task->delete();
            return redirect('/');
    }
}
