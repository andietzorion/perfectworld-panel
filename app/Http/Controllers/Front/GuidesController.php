<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GuidesController extends Controller
{
    public function getIndex()
    {
        pagetitle( [ trans( 'main.apps.guide' ), settings( 'server_name' ) ] );
        return view( 'front.guide.index' );
    }
}
