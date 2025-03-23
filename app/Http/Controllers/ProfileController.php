<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Event;
use App\Models\Application;
use App\Models\Job;

class ProfileController extends Controller
{
    public function edit(User $user)
    {
        return view('profile.edit', [
            'user' => $user->load('profile'),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile.bio' => 'nullable|string|max:500',
            'profile.location' => 'nullable|string|max:255',
            'profile.website' => 'nullable|url|max:255',
        ]);

        $user->update($request->only('name', 'email'));
        $user->profile->update($request->input('profile'));
        $user->profile()->updateOrCreate([], $request->validated());
        return redirect()->route('dashboard');

        return redirect()->route('profile.edit', $user)->with('status', 'Profile updated!');
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Invalid current password');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit', $user)->with('status', 'Password updated!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('home')->with('status', 'Account deleted!');
    }

    public function show(User $user)
    {
        return view('profile.show', [
            'user' => $user->load('profile', 'skills', 'experiences'),
        ]);
    }

    public function endorse(User $user, Request $request)
    {
        $request->validate([
            'skill_id' => 'required|exists:skills,id',
        ]);

        $user->endorsements()->syncWithoutDetaching($request->skill_id);

        return redirect()->back()->with('status', 'Skill endorsed!');
    }

    public function unendorse(User $user, Request $request)
    {
        $request->validate([
            'skill_id' => 'required|exists:skills,id',
        ]);

        $user->endorsements()->detach($request->skill_id);

        return redirect()->back()->with('status', 'Skill unendorsed');
    }

    public function downloadResume(User $user)
    {
        return response()->download($user->profile->resume_path);
    }

    public function downloadAvatar(User $user)
    {
        return response()->download($user->profile->avatar_path);
    }

    public function downloadCover(User $user)
    {
        return response()->download($user->profile->cover_path);
    }

    public function uploadResume(Request $request, User $user)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf|max:2048',
        ]);

        $user->profile->update([
            'resume_path' => $request->file('resume')->store('resumes'),
        ]);

        return redirect()->back()->with('status', 'Resume uploaded!');
    }

    public function uploadAvatar(Request $request, User $user)
    {
        $request->validate([
            'avatar' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->profile->update([
            'avatar_path' => $request->file('avatar')->store('avatars'),
        ]);

        return redirect()->back()->with('status', 'Avatar uploaded!');
    }

    public function uploadCover(Request $request, User $user)
    {
        $request->validate([
            'cover' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->profile->update([
            'cover_path' => $request->file('cover')->store('covers'),
        ]);

        return redirect()->back()->with('status', 'Cover uploaded!');
    }

    public function removeResume(User $user)
    {
        $user->profile->update([
            'resume_path' => null,
        ]);

        return redirect()->back()->with('status', 'Resume removed!');
    }

    public function removeAvatar(User $user)
    {
        $user->profile->update([
            'avatar_path' => null,
        ]);

        return redirect()->back()->with('status', 'Avatar removed!');
    }

    public function removeCover(User $user)
    {
        $user->profile->update([
            'cover_path' => null,
        ]);

        return redirect()->back()->with('status', 'Cover removed!');
    }

    public function showApplications(User $user)
    {
        return view('profile.applications', [
            'user' => $user->load('applications.job'),
        ]);
    }

    public function showApplication(User $user, Application $application)
    {
        return view('profile.application', [
            'user' => $user,
            'application' => $application->load('job.employer'),
        ]);
    }


    public function showEmployerApplications(User $user)
    {
        return view('profile.employer-applications', [
            'user' => $user->load('employerJobs.applications.user'),
        ]);
    }

    public function showEmployerApplication(User $user, Application $application)
    {
        return view('profile.employer-application
        ', [
            'user' => $user,
            'application' => $application->load('user.profile'),
        ]);
    }

    public function showEmployerJobs(User $user)
    {
        return view('profile.employer-jobs', [
            'user' => $user->load('employerJobs.applications'),
        ]);
    }

    public function showEmployerJob(User $user, Job $job)
    {
        return view('profile.employer-job', [
            'user' => $user,
            'job' => $job->load('applications.user.profile'),
        ]);
    }

    public function showEmployerApplicants(User $user, Job $job)
    {
        return view('profile.employer-applicants', [
            'user' => $user,
            'job' => $job->load('applications.user.profile'),
        ]);
    }

    public function showEmployerApplicant(User $user, Application $application)
    {
        return view('profile.employer-applicant', [
            'user' => $user,
            'application' => $application->load('user.profile'),
        ]);
    }

    public function showEmployerApplicantProfile(User $user, Application $application)
    {
        return view('profile.employer-applicant-profile', [
            'user' => $user,
            'application' => $application->load('user.profile', 'job.employer.profile'),
        ]);
    }

    public function showEmployerApplicantResume(User $user, Application $application)
    {
        return view('profile.employer-applicant-resume', [
            'user' => $user,
            'application' => $application->load('user.profile'),
        ]);
    }

    public function showEmployerApplicantCover(User $user, Application $application)
    {
        return view('profile.employer-applicant-cover', [
            'user' => $user,
            'application' => $application->load('user.profile'),
        ]);
    }

    public function showEmployerApplicantMessage(User $user, Application $application)
    {
        return view('profile.employer-applicant-message', [
            'user' => $user,
            'application' => $application->load('user.profile'),
        ]);
    }

    public function showEmployerApplicantFeedback(User $user, Application $application)
    {
        return view('profile.employer-applicant-feedback', [
            'user' => $user,
            'application' => $application->load('user.profile'),
        ]);
    }

    public function showEmployerApplicantEndorsements(User $user, Application $application)
    {
        return view('profile.employer-applicant-endorsements', [
            'user' => $user,
            'application' => $application->load('user.profile'),
        ]);
    }

    public function showEmployerApplicantSkills(User $user, Application $application)
    {
        return view('profile.employer-applicant-skills', [
            'user' => $user,
            'application' => $application->load('user.profile'),
        ]);
    }

    public function showEmployerApplicantExperiences(User $user, Application $application)
    {
        return view('profile.employer-applicant-experiences', [
            'user' => $user,
            'application' => $application->load('user.profile'),
        ]);
    }

    public function showEmployerApplicantEducation(User $user, Application $application)
    {
        return view('profile.employer-applicant-education', [
            'user' => $user,
            'application' => $application->load('user.profile'),
        ]);
    }

    public function showEmployerApplicantEvents(User $user, Application $application)
    {
        return view('profile.employer-applicant-events', [
            'user' => $user,
            'application' => $application->load('user.profile'),
        ]);
    }

    public function showEmployerApplicantEvent(User $user, Application $application, Event $event)
    {
        return view('profile.employer-applicant-event', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
        ]);
    }

    public function showEmployerApplicantEventFeedback(User $user, Application $application, Event $event)
    {
        return view('profile.employer-applicant-event-feedback', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
        ]);
    }

    public function showEmployerApplicantEventAttendees(User $user, Application $application, Event $event)
    {
        return view('profile.employer-applicant-event-attendees', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
        ]);
    }

    public function showEmployerApplicantEventAttendee(User $user, Application $application, Event $event, User $attendee)
    {
        return view('profile.employer-applicant-event-attendee', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee,
        ]);
    }

    public function showEmployerApplicantEventAttendeeProfile(User $user, Application $application, Event $event, User $attendee)
    {
        return view('profile.employer-applicant-event-attendee-profile', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeResume(User $user, Application $application, Event $event, User $attendee)
    {
        return view('profile.employer-applicant-event-attendee-resume', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeCover(User $user, Application $application, Event $event, User $attendee)
    {
        return view('profile.employer-applicant-event-attendee-cover', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeMessage(User $user, Application $application, Event $event, User $attendee)
    {
        return view('profile.employer-applicant-event-attendee-message', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeFeedback(User $user, Application $application, Event $event, User $attendee)
    {
        return view('profile.employer-applicant-event-attendee-feedback', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeEndorsements(User $user, Application $application, Event $event, User $attendee)
    {
        return view('profile.employer-applicant-event-attendee-endorsements', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeSkills(User $user, Application $application, Event $event, User $attendee)
    {
        return view('profile.employer-applicant-event-attendee-skills', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeExperiences(User $user, Application $application, Event $event, User $attendee)
    {
        return view('profile.employer-applicant-event-attendee-experiences', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeEducation(User $user, Application $application, Event $event, User $attendee)
    {
        return view('profile.employer-applicant-event-attendee-education', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeEvents(User $user, Application $application, Event $event, User $attendee)
    {
        return view('profile.employer-applicant-event-attendee-events', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeEvent(User $user, Application $application, Event $event, User $attendee, Event $attendeeEvent)
    {
        return view('profile.employer-applicant-event-attendee-event', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
            'attendeeEvent' => $attendeeEvent,
        ]);
    }

    public function showEmployerApplicantEventAttendeeEventFeedback(User $user, Application $application, Event $event, User $attendee, Event $attendeeEvent)
    {
        return view('profile.employer-applicant-event-attendee-event-feedback', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
            'attendeeEvent' => $attendeeEvent,
        ]);
    }

    public function showEmployerApplicantEventAttendeeEventAttendees(User $user, Application $application, Event $event, User $attendee, Event $attendeeEvent)
    {
        return view('profile.employer-applicant-event-attendee-event-attendees', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
            'attendeeEvent' => $attendeeEvent,
        ]);
    }

    public function showEmployerApplicantEventAttendeeEventAttendee(User $user, Application $application, Event $event, User $attendee, Event $attendeeEvent, User $attendeeAttendee)
    {
        return view('profile.employer-applicant-event-attendee-event-attendee', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
            'attendeeEvent' => $attendeeEvent,
            'attendeeAttendee' => $attendeeAttendee,
        ]);
    }

    public function showEmployerApplicantEventAttendeeEventAttendeeProfile(User $user, Application $application, Event $event, User $attendee, Event $attendeeEvent, User $attendeeAttendee)
    {
        return view('profile.employer-applicant-event-attendee-event-attendee-profile', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
            'attendeeEvent' => $attendeeEvent,
            'attendeeAttendee' => $attendeeAttendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeEventAttendeeResume(User $user, Application $application, Event $event, User $attendee, Event $attendeeEvent, User $attendeeAttendee)
    {
        return view('profile.employer-applicant-event-attendee-event-attendee-resume', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
            'attendeeEvent' => $attendeeEvent,
            'attendeeAttendee' => $attendeeAttendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeEventAttendeeCover(User $user, Application $application, Event $event, User $attendee, Event $attendeeEvent, User $attendeeAttendee)
    {
        return view('profile.employer-applicant-event-attendee-event-attendee-cover', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
            'attendeeEvent' => $attendeeEvent,
            'attendeeAttendee' => $attendeeAttendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeEventAttendeeMessage(User $user, Application $application, Event $event, User $attendee, Event $attendeeEvent, User $attendeeAttendee)
    {
        return view('profile.employer-applicant-event-attendee-event-attendee-message', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
            'attendeeEvent' => $attendeeEvent,
            'attendeeAttendee' => $attendeeAttendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeEventAttendeeFeedback(User $user, Application $application, Event $event, User $attendee, Event $attendeeEvent, User $attendeeAttendee)
    {
        return view('profile.employer-applicant-event-attendee-event-attendee-feedback', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
            'attendeeEvent' => $attendeeEvent,
            'attendeeAttendee' => $attendeeAttendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeEventAttendeeEndorsements(User $user, Application $application, Event $event, User $attendee, Event $attendeeEvent, User $attendeeAttendee)
    {
        return view('profile.employer-applicant-event-attendee-event-attendee-endorsements', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
            'attendeeEvent' => $attendeeEvent,
            'attendeeAttendee' => $attendeeAttendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeEventAttendeeSkills(User $user, Application $application, Event $event, User $attendee, Event $attendeeEvent, User $attendeeAttendee)
    {
        return view('profile.employer-applicant-event-attendee-event-attendee-skills', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
            'attendeeEvent' => $attendeeEvent,
            'attendeeAttendee' => $attendeeAttendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeEventAttendeeExperiences(User $user, Application $application, Event $event, User $attendee, Event $attendeeEvent, User $attendeeAttendee)
    {
        return view('profile.employer-applicant-event-attendee-event-attendee-experiences', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
            'attendeeEvent' => $attendeeEvent,
            'attendeeAttendee' => $attendeeAttendee->load('profile'),
        ]);
    }

    public function showEmployerApplicantEventAttendeeEventAttendeeEducation(User $user, Application $application, Event $event, User $attendee, Event $attendeeEvent, User $attendeeAttendee)
    {
        return view('profile.employer-applicant-event-attendee-event-attendee-education', [
            'user' => $user,
            'application' => $application->load('user.profile'),
            'event' => $event,
            'attendee' => $attendee->load('profile'),
            'attendeeEvent' => $attendeeEvent,
            'attendeeAttendee' => $attendeeAttendee->load('profile'),
        ]);
    }
}