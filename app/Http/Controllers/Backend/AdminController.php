<?php

namespace App\Http\Controllers\Backend;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $administrators = Admin::query()
            ->orderByDesc('id')
            ->paginate(request()->input('size', 1));

        return view('backend.admin.index', [
            'administrators' => $administrators,
        ]);
    }

    /**
     * Show the administrator profile screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        return view('backend.admin.show', [
            'request' => $request,
            'user' => $request->user(),
        ]);
    }
}
