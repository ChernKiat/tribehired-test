<?php

namespace App\Http\Controllers\SupportSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportSystem\User;

class NewYearController extends Controller
{
    public function create(Request $request)
    {
        return view('modules.support_system.newyear');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'                => 'required',
            'birthday'            => 'required',
            'gender'              => 'required',
            'email'               => 'required|email',
        ];
        $request->validate($rules, [
            // 'pdf.mimetypes' => 'You must upload scanned copy of the document in .pdf format.',
        ]);

        $user = new User();
        $user->user_name             = $request->name;
        $user->birthday_year         = $request->birthday;
        $user->user_gender           = $request->gender;
        $user->user_email            = $request->email;
        $user->save();

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
            'name'                => 'required',
            'to'                  => 'required',
            'message'             => 'required',
        ];
        $request->validate($rules, [
            // 'pdf.mimetypes' => 'You must upload scanned copy of the document in .pdf format.',
        ]);

        $user = User::find($id);
        $user->user_name             = $request->to;
        $user->birthday_year         = $request->message;
        $user->save();

        return redirect()->route('supportsystem.newyear.show', ['id' => $user->id]);
    }

    public function show(Request $request, $id)
    {
        $user = User::find($id);
        return view('modules.support_system.newyear3', compact('user'));
    }
}
