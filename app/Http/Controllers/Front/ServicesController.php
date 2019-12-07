<?php

namespace App\Http\Controllers\Front;

use App\Models\Service;
use App\Models\Transfer;
use Huludini\PerfectWorldAPI\API;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ServicesController extends Controller
{
    public function __construct()
    {
        $this->middleware( 'auth' );

        $this->middleware( 'service.enabled', ['only' => ['postPurchase'] ] );

        $this->middleware( 'selected.character', ['only' => ['postPurchase'] ] );
    }

    public function getIndex()
    {
        pagetitle( [ trans( 'main.apps.services' ), settings( 'server_name' ) ] );
        $services = Service::all();
        return view( 'front.services.index', compact( 'services' ) );
    }

    public function postPurchase( Request $request, Service $service )
    {
        if ( !method_exists( $this, $service->key ) )
        {
            throw new \BadMethodCallException( 'NO_SERVICE: Service ' . $service->key . ' doesn\'t exist.' );
        }
        else
        {
            call_user_func_array( [ $this, $service->key ], [ $request, $service ] );
            return redirect()->back();
        }
    }

    public function checkOnline( $role )
    {
        $result = false;
        $api = new API();
        $totalOnline = $api->getOnlineList();

        foreach( $totalOnline as $online )
        {
            if ( $online['roleid'] == $role )
            {
                $result = true;
            }
        }
        return $result;
    }

    public function broadcast( Request $request, Service $service )
    {
        $this->validate($request, [
            'message' => 'required',
        ]);

        $user = Auth::user();
        $role = $user->characterId();
        $message = $request->message;

        if ( $user->money >= $service->price )
        {
            if ( $this->checkOnline( $role ) )
            {
                $api = new API();

                if ( $api->WorldChat( $role, $message, 9 ) )
                {
                    $user->money = $user->money - $service->price;
                    $user->save();

                    flash()->success( trans( 'services.' . $service->key . '.complete' ) );
                }
                else
                {
                    flash()->error( trans( 'main.server_not_online' ) );
                }
            }
            else
            {
                flash()->error( trans( 'services.must_login' ) );
            }
        }
        else
        {
            flash()->error( trans( 'main.not_enough', ['currency' => strtolower( settings( 'currency_name' ) )] ) );
        }
    }

    public function virtual_to_cubi( Request $request, Service $service )
    {
        $this->validate($request, [
            'quantity' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $role = $user->characterId();

        if ( $user->money >= ( $request->quantity * $service->price ) )
        {
            if ( !$this->checkOnline( $role ) )
            {
                $user->money = $user->money - ( $request->quantity * $service->price );
                $user->save();

                Transfer::create([
                    'user_id' => $user->ID,
                    'zone_id' => 1,
                    'cash' => $request->quantity * 100
                ]);

                flash()->success( trans( 'services.' . $service->key . '.complete' ) );
            }
            else
            {
                flash()->error( trans( 'services.must_logout' ) );
            }
        }
        else
        {
            flash()->error( trans( 'main.not_enough', ['currency' => strtolower( settings( 'currency_name' ) )] ) );
        }
    }

    public function cultivation_change( Request $request, Service $service )
    {
        $user = Auth::user();
        $role = $user->characterId();

        if ( $user->money >= $service->price )
        {
            if ( !$this->checkOnline( $role ) )
            {
                $api = new API();
                if ( $role_data = $api->getRole( $role ) )
                {
                    if ( $role_data['status']['level2'] != 0 )
                    {
                        $role_data['status']['level2'] = ( $role_data['status']['level2'] == 22 ) ? 32 : 22;

                        if ( $api->putRole( $role, $role_data ) )
                        {
                            $user->money = $user->money - $service->price;
                            $user->save();

                            flash()->success( trans( 'services.' . $service->key . '.complete' ) );
                        }
                        else
                        {
                            flash()->error( trans( 'main.server_not_online' ) );
                        }
                    }
                    else
                    {
                        flash()->error( trans( 'services.cultivation_not_unlocked' ) );
                    }
                }
                else
                {
                    flash()->error( trans( 'main.server_not_online' ) );
                }
            }
            else
            {
                flash()->error( trans( 'services.must_logout' ) );
            }
        }
        else
        {
            flash()->error( trans( 'main.not_enough', ['currency' => strtolower( settings( 'currency_name' ) )] ) );
        }
    }

    public function gold_to_virtual( Request $request, Service $service )
    {
        $this->validate($request, [
            'quantity' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $role = $user->characterId();

        if ( !$this->checkOnline( $role ) )
        {
            $api = new API();
            if ( $role_data = $api->getRole( $role ) )
            {
                if ( $role_data['pocket']['money'] > ( $request->quantity * $service->price ) )
                {
                    $role_data['pocket']['money'] = $role_data['pocket']['money'] - ( $request->quantity * $service->price );

                    if ( $api->putRole( $role, $role_data ) )
                    {
                        $user->money = $user->money + $request->quantity;
                        $user->save();

                        flash()->success( trans( 'services.' . $service->key . '.complete', ['quantity' => $request->quantity, 'currency' => strtolower( settings( 'currency_name' ) )] ) );
                    }
                    else
                    {
                        flash()->error( trans( 'main.server_not_online' ) );
                    }
                }
                else
                {
                    flash()->error( trans( 'main.not_enough_gold' ) );
                }
            }
            else
            {
                flash()->error( trans( 'main.server_not_online' ) );
            }
        }
        else
        {
            flash()->error( trans( 'services.must_logout' ) );
        }
    }

    public function level_up( Request $request, Service $service )
    {
        $this->validate($request, [
            'quantity' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $role = $user->characterId();

        if ( $user->money >= ( $request->quantity * $service->price ) )
        {
            if ( !$this->checkOnline( $role ) )
            {
                $api = new API();
                if ( $role_data = $api->getRole( $role ) )
                {
                    if ( ( $role_data['status']['level'] + $request->quantity ) <= settings( 'level_up_cap' ) )
                    {
                        $role_data['status']['level'] = $role_data['status']['level'] + $request->quantity;
                        $role_data['status']['pp'] = $role_data['status']['pp'] + ( $request->quantity * 5 );

                        if ( !in_array( $role_data['base']['cls'], [ 9, 1, 2, 3, 7 ] ) )
                        {
                            $role_data['status']['hp'] = $role_data['status']['hp'] + ( $request->quantity * 20 );
                            $role_data['status']['mp'] = $role_data['status']['mp'] + ( $request->quantity * 28 );
                        }
                        elseif ( !in_array( $role_data['base']['cls'], [ 6, 5 ] ) )
                        {
                            $role_data['status']['hp'] = $role_data['status']['hp'] + ( $request->quantity * 26 );
                            $role_data['status']['mp'] = $role_data['status']['mp'] + ( $request->quantity * 22 );
                        }
                        else
                        {
                            $role_data['status']['hp'] = $role_data['status']['hp'] + ( $request->quantity * 30 );
                            $role_data['status']['mp'] = $role_data['status']['mp'] + ( $request->quantity * 18 );
                        }

                        if ( $api->putRole( $role, $role_data ) )
                        {
                            $user->money = $user->money - ( $request->quantity * $service->price );
                            $user->save();

                            flash()->success( trans( 'services.' . $service->key . '.complete', ['quantity' => $request->quantity] ) );
                        }
                        else
                        {
                            flash()->error( trans( 'main.server_not_online' ) );
                        }
                    }
                    else
                    {
                        flash()->error( trans( 'services.max_level' ) );
                    }
                }
                else
                {
                    flash()->error( trans( 'main.server_not_online' ) );
                }
            }
            else
            {
                flash()->error( trans( 'services.must_logout' ) );
            }
        }
        else
        {
            flash()->error( trans( 'main.not_enough', ['currency' => strtolower( settings( 'currency_name' ) )] ) );
        }

    }

    public function max_meridian( Request $request, Service $service )
    {
        $user = Auth::user();
        $role = $user->characterId();

        if ( $user->money >= $service->price )
        {
            if ( !$this->checkOnline( $role ) )
            {
                $api = new API();
                if ( $role_data = $api->getRole( $role ) )
                {
                    if ( $role_data['status']['level'] >= 40 )
                    {
                        $meridian_hex = '0000005000000000000000000000000500000064000000000000000100000000000000000000000000000000000000000000000000000000';

                        if ( $role_data['status']['meridian_data'] != $meridian_hex )
                        {
                            $role_data['status']['meridian_data'] = $meridian_hex;

                            if ( $api->putRole( $role, $role_data ) )
                            {
                                $user->money = $user->money - $service->price;
                                $user->save();

                                flash()->success( trans( 'services.' . $service->key . '.complete' ) );
                            }
                            else
                            {
                                flash()->error( trans( 'main.server_not_online' ) );
                            }
                        }
                        else
                        {
                            flash()->error( trans( 'services.meridian_maxed' ) );
                        }
                    }
                    else
                    {
                        flash()->error( trans( 'services.not_high_enough_level' ) );
                    }
                }
                else
                {
                    flash()->error( trans( 'main.server_not_online' ) );
                }
            }
            else
            {
                flash()->error( trans( 'services.must_logout' ) );
            }
        }
        else
        {
            flash()->error( trans( 'main.not_enough', ['currency' => strtolower( settings( 'currency_name' ) )] ) );
        }
    }

    public function max_title( Request $request, Service $service )
    {
        $user = Auth::user();
        $role = $user->characterId();

        if ( $user->money >= $service->price )
        {
            if ( !$this->checkOnline( $role ) )
            {
                $api = new API();
                if ( $role_data = $api->getRole( $role ) )
                {
                    if ( $role_data['status']['level'] >= 40 )
                    {
                        $title_hex = '0e051c0100000e050d058c050f05150516051705180519051a051b051c051d0583051e051f05200521052205230524052505840526052705280529052a052b052c052d052e052f0530053105320533053405350536053705380539053a053b053c053d053e053f054005410542054305850544058705450546054705480549054a0586054b054c054d054e054f058805500551055205530554055505560557055805590589055a055b055c055d055e055f0560056105620563056405650566056705680569056a056b056c056d056e056f05700571057205730574057505760577058b0578058a0579057a05c606cd06ce06cf06d006d106d206dd06de0649074a074b075d075e075f07600761076307640765076b076c076d076e076f077007710772077307d807da07db07dc07f707fe07ff0700080108020803080408050806080708080809080a080b080c080f081008110812081308140815081608170819081a081b081c081d08380839088d058e058f051106140615062b063e0666066706680669066a066b066c066d066e066f0670067106720673067406750676067706780679067a067b067c067d067e067f0680068106820683068406850686068706880689068a068b068c068d068e068f06900691069206930694069b069c069f06a006a106a206a306a406a506a606a706a806a906aa06ab06ac06ad06ae06af06b006b106b206b306b406b506b606b706b806b906ba06bb06bc06bd06be06bf06c006c106c206c306c406c5067b057c057d057e057f0580058105820500000000';

                        if ( $role_data['status']['title_data'] != $title_hex )
                        {
                            $role_data['status']['title_data'] = $title_hex;

                            if ( $api->putRole( $role, $role_data ) )
                            {
                                $user->money = $user->money - $service->price;
                                $user->save();

                                flash()->success( trans( 'services.' . $service->key . '.complete' ) );
                            }
                            else
                            {
                                flash()->error( trans( 'main.server_not_online' ) );
                            }
                        }
                        else
                        {
                            flash()->error( trans( 'services.title_maxed' ) );
                        }
                    }
                    else
                    {
                        flash()->error( trans( 'services.not_high_enough_level' ) );
                    }
                }
                else
                {
                    flash()->error( trans( 'main.server_not_online' ) );
                }
            }
            else
            {
                flash()->error( trans( 'services.must_logout' ) );
            }
        }
        else
        {
            flash()->error( trans( 'main.not_enough', ['currency' => strtolower( settings( 'currency_name' ) )] ) );
        }
    }


    public function reset_title( Request $request, Service $service )
    {
        $user = Auth::user();
        $role = $user->characterId();

        if ( $user->money >= $service->price )
        {
            if ( !$this->checkOnline( $role ) )
            {
                $api = new API();
                if ( $role_data = $api->getRole( $role ) )
                {
                    if ( $role_data['status']['level'] >= 40 )
                    {
                        $title_hex = '';

                        if ( $role_data['status']['title_data'] != $title_hex )
                        {
                            $role_data['status']['title_data'] = $title_hex;

                            if ( $api->putRole( $role, $role_data ) )
                            {
                                $user->money = $user->money - $service->price;
                                $user->save();

                                flash()->success( trans( 'services.' . $service->key . '.complete' ) );
                            }
                            else
                            {
                                flash()->error( trans( 'main.server_not_online' ) );
                            }
                        }
                        else
                        {
                            flash()->error( trans( 'services.title_maxed' ) );
                        }
                    }
                    else
                    {
                        flash()->error( trans( 'services.not_high_enough_level' ) );
                    }
                }
                else
                {
                    flash()->error( trans( 'main.server_not_online' ) );
                }
            }
            else
            {
                flash()->error( trans( 'services.must_logout' ) );
            }
        }
        else
        {
            flash()->error( trans( 'main.not_enough', ['currency' => strtolower( settings( 'currency_name' ) )] ) );
        }
    }


    public function reset_exp( Request $request, Service $service )
    {
        $user = Auth::user();
        $role = $user->characterId();

        if ( $user->money >= $service->price )
        {
            if ( !$this->checkOnline( $role ) )
            {
                $api = new API();
                if ( $role_data = $api->getRole( $role ) )
                {
                    $role_data['status']['exp'] = 0;

                    if ( $api->putRole( $role, $role_data ) )
                    {
                        $user->money = $user->money - $service->price;
                        $user->save();

                        flash()->success( trans( 'services.' . $service->key . '.complete' ) );
                    }
                    else
                    {
                        flash()->error( trans( 'main.server_not_online' ) );
                    }
                }
                else
                {
                    flash()->error( trans( 'main.server_not_online' ) );
                }
            }
            else
            {
                flash()->error( trans( 'services.must_logout' ) );
            }
        }
        else
        {
            flash()->error( trans( 'main.not_enough', ['currency' => strtolower( settings( 'currency_name' ) )] ) );
        }
    }

    public function reset_sp( Request $request, Service $service )
    {
        $user = Auth::user();
        $role = $user->characterId();

        if ( $user->money >= $service->price )
        {
            if ( !$this->checkOnline( $role ) )
            {
                $api = new API();
                if ( $role_data = $api->getRole( $role ) )
                {
                    $role_data['status']['sp'] = 0;

                    if ( $api->putRole( $role, $role_data ) )
                    {
                        $user->money = $user->money - $service->price;
                        $user->save();

                        flash()->success( trans( 'services.' . $service->key . '.complete' ) );
                    }
                    else
                    {
                        flash()->error( trans( 'main.server_not_online' ) );
                    }
                }
                else
                {
                    flash()->error( trans( 'main.server_not_online' ) );
                }
            }
            else
            {
                flash()->error( trans( 'services.must_logout' ) );
            }
        }
        else
        {
            flash()->error( trans( 'main.not_enough', ['currency' => strtolower( settings( 'currency_name' ) )] ) );
        }
    }

    public function reset_stash_password( Request $request, Service $service )
    {
        $user = Auth::user();
        $role = $user->characterId();

        if ( $user->money >= $service->price )
        {
            if ( !$this->checkOnline( $role ) )
            {
                $api = new API();
                if ( $role_data = $api->getRole( $role ) )
                {
                    if ( $role_data['status']['storehousepasswd'] != NULL )
                    {
                        $role_data['status']['storehousepasswd'] = '';

                        if ( $api->putRole( $role, $role_data ) )
                        {
                            $user->money = $user->money - $service->price;
                            $user->save();

                            flash()->success( trans( 'services.' . $service->key . '.complete' ) );
                        }
                        else
                        {
                            flash()->error( trans( 'main.server_not_online' ) );
                        }
                    }
                    else
                    {
                        flash()->error( trans( 'services.no_stash_password' ) );
                    }
                }
                else
                {
                    flash()->error( trans( 'main.server_not_online' ) );
                }
            }
            else
            {
                flash()->error( trans( 'services.must_logout' ) );
            }
        }
        else
        {
            flash()->error( trans( 'main.not_enough', ['currency' => strtolower( settings( 'currency_name' ) )] ) );
        }
    }

    public function teleport( Request $request, Service $service )
    {
        $user = Auth::user();
        $role = $user->characterId();

        if ( $user->money >= $service->price )
        {
            if ( !$this->checkOnline( $role ) )
            {
                $api = new API();
                if ( $role_data = $api->getRole( $role ) )
                {
                    $role_data['status']['posx'] = settings( 'teleport_x' );
                    $role_data['status']['posy'] = settings( 'teleport_y' );
                    $role_data['status']['posz'] = settings( 'teleport_z' );
                    $role_data['status']['worldtag'] = settings( 'teleport_world_tag' );

                    if ( $api->putRole( $role, $role_data ) )
                    {
                        $user->money = $user->money - $service->price;
                        $user->save();

                        flash()->success( trans( 'services.' . $service->key . '.complete' ) );
                    }
                    else
                    {
                        flash()->error( trans( 'main.server_not_online' ) );
                    }
                }
                else
                {
                    flash()->error( trans( 'main.server_not_online' ) );
                }
            }
            else
            {
                flash()->error( trans( 'services.must_logout' ) );
            }
        }
        else
        {
            flash()->error( trans( 'main.not_enough', ['currency' => strtolower( settings( 'currency_name' ) )] ) );
        }
    }
}
