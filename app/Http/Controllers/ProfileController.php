<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\User;

class ProfileController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        //Audit Log
        $this->auditLogsShort('View Page Profile');
        return view('profile.index');
    }
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photoProfile' => 'mimes:jpg,jpeg,png|max:5240',
        ]);
        $user = auth()->user();
        $folderPath = public_path('storage/profileImg');

        // Create folder if not exists
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }
        DB::beginTransaction();
        try {
            // Delete previous photo
            if ($user->photo_path && File::exists(public_path($user->photo_path))) {
                File::delete(public_path($user->photo_path));
            }
            // Save new photo
            $photo = $request->file('photoProfile');
            $photoName = $photo->hashName();
            $photo->move($folderPath, $photoName);
            $photoPath = 'storage/profileImg/' . $photoName;
            // Update user
            User::where('id', auth()->user()->id)->update(['photo_path' => $photoPath]);

            // Audit Log
            $this->auditLogsShort('Update Profile Image User ID: ' . auth()->user()->id);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Updated Your Profile Image']);
        } catch (\Exception $e) {
            DB::rollBack();
            // Clean up uploaded file if error occurs
            if (isset($photoPath) && File::exists(public_path($photoPath))) {
                File::delete(public_path($photoPath));
            }
            return redirect()->back()->with(['fail' => 'Failed to Update Your Profile Image!']);
        }
    }
}
