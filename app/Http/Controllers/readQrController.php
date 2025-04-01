<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;

class readQrController extends Controller
{
    public function index($id)
    {
        // $id_user = Auth::user()->id;
        // $leaves = Leave::where('enhancer', $id_user)->with('user')->value('id');
        $l = Leave::find($id);
        // $name = User::where('id', $id_user)->value('name');
        $leaves = null;
        // dd($leaves);
        return view('pages.pegawai.leaves.print', compact('l', 'leaves'));
    }
}
