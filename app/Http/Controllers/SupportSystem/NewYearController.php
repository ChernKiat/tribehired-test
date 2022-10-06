<?php

namespace App\Http\Controllers\SupportSystem;

use App\Http\Controllers\Controller;
use App\Models\SupportSystem\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewYearController extends Controller
{
    public function create(Request $request)
    {
        return view('modules.support_system.newyear');
    }

    public function store(Request $request)
    {
        $rules = [
            'image'               => 'required|mimetypes:image/jpeg,image/png',
            'name'                => 'required',
            'birthday'            => 'required',
            'gender'              => 'required',
            'email'               => 'required|email',
        ];
        $request->validate($rules, [
            'image.mimetypes'     => 'You must upload image in .jpg or .png format.',
        ]);

        $user = new User();
        $user->user_name             = $request->name;
        $user->birthday_year         = $request->birthday;
        $user->user_gender           = $request->gender;
        $user->user_email            = $request->email;
        $user->save();

        if ($request->has('image')) {
            $file = $request->file('image');
            $random = Carbon::now()->timestamp . Str::random(10);
            $name = pathinfo(preg_replace('/[^A-Za-z0-9. \-]/', '', $file->getClientOriginalName()), PATHINFO_FILENAME);
            $extension = strtolower($file->getClientOriginalExtension());
            $newName = "{$name}-{$random}.{$extension}";
            $file->move(public_path("mySupportSystem/newyear/users/{$user->id}"), $newName);

            $user->user_image        = $newName;
            $user->save();
        }

        return redirect()->route('supportsystem.newyear.edit', ['id' => $user->id]);
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        return view('modules.support_system.newyear2', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'to'                  => 'required',
            'message'             => 'required',
        ];
        $request->validate($rules, [
            // 'pdf.mimetypes' => 'You must upload scanned copy of the document in .pdf format.',
        ]);

        $user = User::find($id);
        $user->to                 = $request->to;
        $user->message            = $request->message;
        $user->save();
        $user->generateVoucherCode();

        return redirect()->route('supportsystem.newyear.show', ['id' => $user->id]);
    }

    public function show(Request $request, $id)
    {
        $user = User::find($id);
        return view('modules.support_system.newyear3', compact('user'));
    }
}