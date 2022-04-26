<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $referralsCommission = Configuration::getReferralsCommission();
        $poolReward = Configuration::getPoolReward();
        $itemsAmountLimit = Configuration::getItemsAmountLimit();

        return Inertia::render('Admin/Configuration/Index', compact('referralsCommission', 'poolReward', 'itemsAmountLimit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'referido.*.*' => ['required', 'numeric', 'min:0', 'max:99'],
        ],[
            'referido.*.*.required' => 'Debe ingresar el valor de la comisión del :attribute.',
            'referido.*.*.numeric' => 'EL valor de la comisión del :attribute debe ser un número válido.',
            'referido.*.*.min' => 'El valor de la comisión del :attribute debe ser al menos 0.',
            'referido.*.*.max' => 'El valor de la comisión del :attribute debe ser máximo 99.',
        ]);

        dd($request->all());

        $configuration = Configuration::getConfig();

        $configuration->referrals_commission = $request->commissions;
        $configuration->save();

        return response()->with('success', '¡Usuario agregado existosamente!');
    }
}
