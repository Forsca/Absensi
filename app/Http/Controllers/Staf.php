<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\File;

class Staf extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('staf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function proses()
    {
        $zk = new ZKTeco('192.168.254.26');
        $zk->connect();
        $zk->enableDevice();
        $attendance = $zk->getAttendance();
        $user = $zk->getUser(); 
        $data = json_encode($attendance);
        $user = json_encode($user);
        File::put('Absen/data_staf.txt', $data);
        File::put('Absen/user_staf.txt', $user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
