<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Job;
use App\Models\User;

class EmployerController extends Controller
{
    public function dashboard()
    {
        // Fetch dashboard data with eager loading
        return view('dashboard', [
            'profile' => Auth::user()->profile,
            'jobs' => Auth::user()->jobs
        ]);
    }

    public function createJob()
    {
        return view('jobs.create');
    }

    public function storeJob(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'type' => 'required',
            'salary' => 'required|numeric',
        ]);

        return redirect('dashboard')->with('success', 'Job created successfully!');
    }

    public function updateJob(Request $request, Job $job)
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

    public function deleteJob(Job $job)
    {
        $job->delete();

        return redirect('dashboard')->with('success', 'Job deleted successfully!');
    }

    public function showJob(Job $job)
    {
        return view('jobs.show', [
            'job' => $job,
        ]);
    }

    public function editJob(Job $job)
    {
        return view('jobs.edit', [
            'job' => $job,
        ]);
    }

    public function searchJob(Request $request)
    {
        $request->validate([
            'query' => 'required',
        ]);

        $jobs = Job::where('title', 'like', "%{$request->query}%")
            ->orWhere('description', 'like', "%{$request->query}%")
            ->get();

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

    public function applicants(Job $job)
    {
        return view('applications.applicants', [
            'applicants' => $job->applicants()->paginate(10),
        ]);
    }

    public function hire(Request $request, Job $job)
    {
        $request->validate([
            'applicant_id' => 'required',
        ]);

        $job->applications()->where('user_id', $request->applicant_id)->update([
            'status' => 'hired',
        ]);

        return redirect('dashboard')->with('success', 'Applicant hired successfully!');
    }

    public function fire(Request $request, Job $job)
    {
        $request->validate([
            'applicant_id' => 'required',
        ]);

        $job->applications()->where('user_id', $request->applicant_id)->update([
            'status' => 'fired',
        ]);

        return redirect('dashboard')->with('success', 'Applicant fired successfully!');
    }

    public function upcomingJobs()
    {
        return view('jobs.upcoming', [
            'jobs' => Job::upcoming()->paginate(10),
        ]);
    }

    public function pastJobs()
    {
        return view('jobs.past', [
            'jobs' => Job::past()->paginate(10),
        ]);
    }


    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('dashboard');
    }


    public function logout()
    {
        Auth::logout();

        return redirect('login');
    }

    public function verifyEmail(Request $request)
    {
        $request->user()->markEmailAsVerified();
        return redirect('dashboard');
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification email sent!');
    }

    public function showProfile()
    {
        return view('profile.show', [
            'profile' => Auth::user()->profile,
        ]);
    }
}
