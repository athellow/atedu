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
    public function index(Request $request)
    {
        $params = $request->input();

        $map = [];

        if(!empty($params['name'])) {
            $map[] = ['name', 'like', '%'. $params['name'] .'%'];
        }
        if(!empty($params['email'])) {
            $map[] = ['email', 'like', '%'. $params['email'] .'%'];
        }

        $administrators = Admin::query()
            ->where($map)
            ->orderByDesc('id')
            ->paginate(request()->input('size', 1));

        return view('backend.admin.index', [
            'administrators' => $administrators,
            'param' => $params
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
