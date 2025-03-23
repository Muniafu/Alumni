<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Job;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
   public function index()
   {
       return view('jobs.index', [
           'jobs' => Job::with('employer')->latest()->paginate(10),
       ]);
   }

    public function show(Job $job)
    {
         return view('jobs.show', [
              'job' => $job,
         ]);
    }

    public function apply(Job $job)
    {
        $job->applications()->create([
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Application submitted successfully!');
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function edit(Job $job)
    {
        return view('jobs.edit', [
            'job' => $job,
        ]);
    }

    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'type' => 'required',
            'salary' => 'required|numeric',
        ]);

        $job->update($request->all());

        return redirect('dashboard')->with('success', 'Job updated successfully!');
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect('dashboard')->with('success', 'Job deleted successfully!');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required',
        ]);

        $jobs = Job::where('title', 'like', "%{$request->query}%")
            ->orWhere('description', 'like', "%{$request->query}%")
            ->orWhere('location', 'like', "%{$request->query}%")
            ->orWhere('type', 'like', "%{$request->query}%")
            ->orWhere('salary', 'like', "%{$request->query}%")
            ->with('employer')
            ->latest()
            ->paginate(10);

        return view('jobs.index', [
            'jobs' => $jobs,
        ]);
    }

    public function applications(Job $job)
    {
        return view('applications.index', [
            'applications' => $job->applications()->latest()->paginate(10),
        ]);
    }

    public function approveApplication(Job $job, Application $application)
    {
        $application->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'Application approved successfully!');
    }

    public function rejectApplication(Job $job, Application $application)
    {
        $application->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Application rejected successfully!');
    }

    public function cancelApplication(Job $job, Application $application)
    {
        $application->delete();

        return back()->with('success', 'Application cancelled successfully!');
    }

    public function markAsRead(Job $job, Application $application)
    {
        $application->update([
            'read_at' => now(),
        ]);

        return back();
    }

    public function markAsUnread(Job $job, Application $application)
    {
        $application->update([
            'read_at' => null,
        ]);

        return back();
    }
}