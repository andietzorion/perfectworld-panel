<?php

namespace App\Http\Controllers\Admin;

use Efriandika\LaravelSettings\Facades\Settings;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\Payment;
use App\Models\User;
use Input;

class DonateController extends Controller
{
    public function index()
    {
        pagetitle( [ trans( 'main.apps.donate' ), settings( 'server_name' ) ] );

        $status = Input::get('status');
        $datas = [];
        if($status == "berhasil"){
            $datas = Payment::where('status', 'berhasil')->with('user')->paginate(8);
        }else{
            $datas = Payment::where('status', 'pending')->with('user')->paginate(8);
        }
        return view( 'admin.donate.view', compact( 'datas' ) );
    }
    public function create()
    {}
    public function store()
    {}
    public function show($id)
    {}
    public function edit($id)
    {
        $datas = Payment::with('user')->find($id);
        $datas->status = 'berhasil';
        $datas->save();

        $amount = round($datas->amount / settings('form_per'));

        $user = User::find($datas->user->ID);
        $user->money = $user->money + $amount;
        $user->save();
        phpmailer_donate($datas);

        flash()->success('Donate success.');
        return redirect( 'admin/donate' );
    }

    public function update()
    {}
    public function destroy($id)
    {
        $q = Payment::find($id);
        $q->delete();

        flash()->success('Data has been deleted.');
        return redirect( 'admin/donate' );
    }

    public function getSettings()
    {
        pagetitle( [ trans( 'main.settings' ), trans( 'main.apps.donate' ), settings( 'server_name' ) ] );
        return view( 'admin.donate.settings' );
    }

    public function postPaypalSettings( Request $request )
    {
        $this->validate($request, [
            'paypal_client_id' => 'required',
            'paypal_secret' => 'required',
            'paypal_currency' => 'required',
            'paypal_min' => 'required|numeric|min:1',
            'paypal_per' => 'required|numeric|min:1'
        ]);

        Settings::set( 'paypal_double', $request->paypal_double );
        Settings::set( 'paypal_client_id', $request->paypal_client_id );
        Settings::set( 'paypal_secret', $request->paypal_secret );
        Settings::set( 'paypal_currency', $request->paypal_currency );
        Settings::set( 'paypal_min', $request->paypal_min );
        Settings::set( 'paypal_per', $request->paypal_per );
        Settings::set( 'paypal_status', $request->paypal_status );

        flash()->success( trans( 'main.settings_saved' ) );

        return redirect( 'admin/donate/settings' );
    }

    public function postPaymentwallSettings( Request $request )
    {
        $this->validate($request, [
            'paymentwall_link' => 'required',
            'paymentwall_key' => 'required',
        ]);

        Settings::set( 'paymentwall_double', $request->paymentwall_double );

        if ( str_contains( $request->paymentwall_link, 'iframe' ) )
        {
            preg_match( '/src="([^"]+)"/', $request->paymentwall_link, $match );
            Settings::set( 'paymentwall_link', $match[1] );
        }
        else
        {
            Settings::set( 'paymentwall_link', $request->paymentwall_link );
        }

        Settings::set( 'paymentwall_key', $request->paymentwall_key );
        Settings::set( 'paymentwall_status', $request->paymentwall_status );

        flash()->success( trans( 'main.settings_saved' ) );

        return redirect( 'admin/donate/settings' );

    }

    public function postForm(Request $request)
    {
        // return $request->form_status;
        $this->validate($request, [
            'form_min'      => 'required',
            'form_per'      => 'required',
            'debit_nama'    => 'required',
            'debit_bank'    => 'required',
            'debit_nomor'   => 'required'
        ]);

        Settings::set( 'form_min', $request->form_min );
        Settings::set( 'form_per', $request->form_per );
        Settings::set( 'form_status', $request->form_status );

        Settings::set( 'debit_nama', $request->debit_nama );
        Settings::set( 'debit_bank', $request->debit_bank );
        Settings::set( 'debit_nomor', $request->debit_nomor );

        flash()->success( trans( 'main.settings_saved' ) );

        return redirect( 'admin/donate/settings' );
    }
}
