<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\AdminRequest;
use App\Models\Admin;
use App\Models\AdminRole;
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
            ->paginate(request()->input('size', 3));

        return view('backend.admin.index', [
            'administrators' => $administrators,
            'param' => $params
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = AdminRole::query()->select(['id', 'title'])->get();

        return view('backend.admin.create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Backend\AdminRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminRequest $request, Admin $admin)
    {
        $admin->fill($request->filldata())->save();

        $admin->roles()->sync($request->input('role_ids', []));

        return redirect(route('admin.index'))->with('success', '添加成功.');;
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::query()->where('id', $id)->firstOrFail();
        // $admin['role_ids'] = $admin->roles->pluck('id')->toArray();

        $roles = AdminRole::query()->select(['id', 'title'])->get();
        
        return view('backend.admin.edit', [
            'id' => $id,
            'admin' => $admin,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Backend\AdminRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminRequest $request, $id)
    {
        $administrator = Admin::query()->where('id', $id)->firstOrFail();

        $administrator->fill($request->filldata())->save();

        $administrator->roles()->sync($request->input('role_ids', []));

        return redirect(route('admin.index'))->with('success', '更新成功.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $administrator = Admin::query()->where('id', $id)->firstOrFail();
        if ($administrator->isSuper()) 
            return redirect(route('admin.index'))->withErrors(__('当前用户是超级管理员账户无法删除'));
        
        if($administrator->delete())
            $administrator->roles()->detach();

        return redirect(route('admin.index'))->with('success', '删除成功.');
    }
}
