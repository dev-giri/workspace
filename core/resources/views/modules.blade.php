@extends('voyager::master')
@section('page_title', __('voyager::generic.viewing').' Modules')
@section('css')
    <style type="text/css">
        .page-title{
            background-image: linear-gradient(120deg, #f093fb 0%, #f5576c 100%);
            color:#fff;
            width:100%;
            border-radius:3px;
            margin-top: 25px;
            margin-bottom: 15px;
            overflow:hidden;
        }
        .page-title small{
            margin-left:10px;
            color:rgba(255, 255, 255, 0.85);
        }
        .page-title:after {
            content: '';
            width: 110%;
            background: rgba(255, 255, 255, 0.1);
            position: absolute;
            bottom: -24px;
            z-index: 9;
            display: block;
            transform: rotate(-2deg);
            height: 50px;
            right: 0px;
        }
        .page-title:before {
            content: '';
            width: 110%;
            background: rgba(0, 0, 0, 0.04);
            position: absolute;
            top: -20px;
            z-index: 9;
            display: block;
            transform: rotate(2deg);
            height: 50px;
            left: 0px;
        }
    </style>
@endsection
@section('page_header')

	<div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-plug"></i>Modules <small>You have total {{count($modules)}} plugins / modules</small>
        </h1>
    </div>
    
@stop
@section('content')
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped database-tables">
                                <thead>
                                    <tr> 
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th style="text-align:right;min-width: 210px;">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($modules as $module)
                                    <tr> 
                                        <td>
                                            <p class="name">
                                                <a href="/{{$module->getLowerName()}}" class="desctable" target="_blank">
                                                   <b>{{$module->getName()}}</b> <!-- {{$module->getPath()}} -->
                                                </a>
                                            </p>
                                        </td>

                                        <td>
                                            <div class="">
                                                {{$module->getDescription()}}
                                            </div>
                                        </td>

                                        <td>
                                            <div class="bread_actions">
                                                <span class="badge badge-md" style="background-color: @if(!$module->isEnabled()) #e20a0f @else #2f8516 @endif ;">@if(!$module->isEnabled()) Disabled @else Enabled @endif</span>
                                            </div>
                                        </td>

                                        <td class="actions">
                                            <form role="form" method="POST" action="{{ route('modules.delete-module') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="module" value="{{$module->getLowerName()}}">
                                                <button class="btn btn-danger btn-sm pull-right delete_table ">
                                                   <i class="voyager-trash"></i> Remove
                                                </button>
                                            </form>    
                                            <form role="form" method="POST" action="{{ route('modules.update-status') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="module" value="{{$module->getLowerName()}}">
                                                @if(!$module->isEnabled()) 
                                                    <button type="submit" class="btn btn-sm btn-success pull-right" style="display:inline; margin-right:10px;">
                                                       <i class="voyager-warning"></i> Enable
                                                    </button>
                                                @else 
                                                    <button type="submit" class="btn btn-sm btn-warning pull-right" style="display:inline; margin-right:10px;">
                                                       <i class="voyager-warning"></i> Disable
                                                    </button>
                                                @endif
                                                
                                            </form>
                                            <form role="form" method="POST" action="{{ route('modules.update-package') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="module" value="{{$module->getLowerName()}}">
                                                <button type="submit" class="btn btn-sm btn-primary pull-right desctable" style="display:inline; margin-right:10px;">
                                                   <i class="voyager-cannon"></i> Update
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                     
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                            
            </div>
        </div>
    </div>

@endsection