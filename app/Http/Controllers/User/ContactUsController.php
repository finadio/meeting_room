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
    public function index(Request $request)
    {
        $query = ContactFormSubmission::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%") ;
        }
        $submissions = $query->latest()->paginate(5)->appends(['search' => $request->input('search')]);
        // AJAX: jika request expects JSON, return partial view
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.contact.partials.table', compact('submissions'))->render()
            ]);
        }
        return view('admin.contact.index', compact('submissions'));
    }

    public function submitForm(Request $request)
    {
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

        $emailError = null;
        try {
            // Send email
            Mail::to('Nischal060@gmail.com')->send(new ContactFormMail(
                $request->input('name'),
                $request->input('email'),
                $request->input('subject'),
                $request->input('message')
            ));
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
            $emailError = 'Email gagal dikirim, tapi data Anda sudah tersimpan.';
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'warning' => $emailError
            ]);
        }

        Session::flash('success', $emailError ? $emailError : 'Thank you for the form submission. An admin will reach out soon.');
        return redirect()->back();
    }

    public function show(ContactFormSubmission $contactFormSubmission)
        {
            // Opsional: Tandai notifikasi terkait sebagai sudah dibaca saat detail dilihat
            Notification::where('notifiable_type', ContactFormSubmission::class)
                        ->where('notifiable_id', $contactFormSubmission->id)
                        ->update(['is_read' => true]);

            return view('admin.contact.show', compact('contactFormSubmission'));
        }

    public function replySubmission(Request $request, $id)
    {
        $request->validate([
            'reply_message' => 'required|string',
        ]);

        $submission = ContactFormSubmission::findOrFail($id);
        $adminName = $request->user() ? $request->user()->name : 'Admin';

        // Kirim email balasan ke user
        try {
            \Mail::to($submission->email)->send(new \App\Mail\ContactReplyMail(
                $submission->name,
                $submission->email,
                $request->input('reply_message'),
                $adminName
            ));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim balasan kontak: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengirim email balasan ke user.');
        }

        // Update status balasan
        $submission->is_replied = true;
        $submission->replied_at = now();
        $submission->reply_message = $request->input('reply_message');
        $submission->replied_by = $request->user() ? $request->user()->id : null;
        $submission->save();

        return redirect()->back()->with('success', 'Balasan berhasil dikirim ke user.');
    }

    public function destroy($id)
    {
        $submission = \App\Models\ContactFormSubmission::findOrFail($id);
        $submission->delete();
        return redirect()->back()->with('success', 'Submission kontak berhasil dihapus.');
    }

}
