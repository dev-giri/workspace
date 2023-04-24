<?php

namespace Workspace\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use Nwidart\Modules\Facades\Module;

class ModuleManageController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $modules = Module::all();
        //dd($modules, Module::collections());
       // dd(Voyager::actions());
        return view('workspace::modules', compact('modules'));
    }

    public function updatePackage(Request $request)
    {
        if(isset($request->module)){
            $module = Module::find($request->module);

           dd($request->module, $module->getComposerAttr('require'));
        }
        dd($request);
        return redirect()
            ->back()
            ->with([
                'message'    => __('voyager::generic.successfully_updated'),
                'alert-type' => 'success',
            ]);
    }

    public function updateStatus(Request $request)
    {
        if(isset($request->module)){
            $module = Module::find($request->module);
            if($module->isEnabled()){
                $module->disable();
            }else{
                $module->enable();
            }
        }
        //dd($request);
        return redirect()
            ->back()
            ->with([
                'message'    => __('voyager::generic.successfully_updated'),
                'alert-type' => 'success',
            ]);
    }

    public function deleteModule(Request $request)
    {
        if(isset($request->module)){
            $module = Module::find($request->module);
            $module->delete();
        }
        //dd($request);
        return redirect()
            ->back()
            ->with([
                'message'    => __('voyager::generic.successfully_deleted'),
                'alert-type' => 'success',
            ]);
    }
}