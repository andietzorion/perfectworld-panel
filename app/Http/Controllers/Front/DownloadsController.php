<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DownloadsController extends Controller
{
    public function getIndex()
    {
        pagetitle( [ trans( 'main.apps.download' ), settings( 'server_name' ) ] );
        return view( 'front.download.index' );
    }
}
