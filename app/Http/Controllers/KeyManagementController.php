<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KeyManagementController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $keys = ApiKey::all();
        return view('keys', ['keys' => $keys]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $key = new ApiKey;
        $key->name = $request->get('name');
        $key->cid = $request->get('cid');
        $key->division = $request->get('division') == '' ? null : $request->get('division');
        $key->subdivision = $request->get('subdivision') == '' ? null : $request->get('subdivision');
        $key->key = md5(rand());
        $key->save();

        return redirect()->route('key-management.index');
    }

    /**
     * @param $id
     * @return View
     */
    public function edit($id): View
    {
        $keys = ApiKey::all();
        $key = ApiKey::query()->find($id);
        return view('keys', ['keys' => $keys, 'key' => $key]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $key = ApiKey::query()->find($id);
        $key->name = $request->post('name');
        $key->cid = $request->post('cid');
        $key->division = $request->get('division') == '' ? null : $request->get('division');
        $key->subdivision = $request->get('subdivision') == '' ? null : $request->get('subdivision');
        $key->save();

        return redirect()->route('key-management.index');
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        ApiKey::query()->where('id', $id)->delete();

        return redirect()->route('key-management.index');
    }
}
