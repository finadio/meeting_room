<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;
use App\Models\ContactFormSubmission;
use App\Models\Notification; // Pastikan ini ada
use Illuminate\Support\Facades\Mail;


class ContactUsController extends Controller
{
    public function showForm()
    {
        return view('user.contact_us.contact_us');
    }

    //For Admin
    public function index()
    {
        $submissions = ContactFormSubmission::latest()->paginate(5);

        return view('admin.contact.index', compact('submissions'));
    }

    public function submitForm(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'string',
                'subject' => 'required',
            ]);

            // Save the form data to the database
            $submission = new ContactFormSubmission();
            $submission->user_id = $request->user() ? $request->user()->id : null;
            $submission->name = $request->input('name');
            $submission->email = $request->input('email');
            $submission->subject = $request->input('subject');
            $submission->message = $request->input('message');
            $submission->save();

            // Buat notifikasi untuk admin tentang pengiriman formulir kontak baru
            Notification::create([
                'user_id' => $submission->user_id, // Gunakan user_id dari submission jika ada
                'notifiable_type' => ContactFormSubmission::class,
                'notifiable_id' => $submission->id,
                'message' => 'Pengiriman formulir kontak baru dari ' . $submission->name . '.',
                'type' => 'contact_form_submission',
                'is_read' => false,
            ]);

            // Send email
            Mail::to('Nischal060@gmail.com')->send(new ContactFormMail(
                $request->input('name'),
                $request->input('email'),
                $request->input('subject'),
                $request->input('message')
            ));

            Session::flash('success', 'Thank you for the form submission. An admin will reach out soon.');

            // Redirect back to the form page
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());

            return redirect()->back()->withErrors(['email' => 'Failed to send email. Please try again.']);
        }
    }

    public function show(ContactFormSubmission $contactFormSubmission)
        {
            // Opsional: Tandai notifikasi terkait sebagai sudah dibaca saat detail dilihat
            Notification::where('notifiable_type', ContactFormSubmission::class)
                        ->where('notifiable_id', $contactFormSubmission->id)
                        ->update(['is_read' => true]);

            return view('admin.contact.show', compact('contactFormSubmission'));
        }

}
