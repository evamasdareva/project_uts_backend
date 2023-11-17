<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    //mendapatkan semua data pegawai
    public function index()
    {$employees = Employees::all();
    
    //jika data kosong maka kirim status code 204
    if($employees->isEmpty()){
        $data = [
            "message" => "Resource is empty"
        ];

        return response()->json($data, 204);
    }

    $data = [
        "message" => "Get all employees",
        "data" => $employees
    ];

    //kirim data (json) dan response code 200
    return response()->json($data, 200);
}

public function show($id){
    $employees = Employees::find($id);

    // jika data yang dicari tidak ada, kirim kode 404
    if(!$employees){
        $data = [
            "message" => "Data not found"
        ];

        return response()->json($data, 404);
    }

    $data = [
        "message" => "Show detail resource",
        "data" => $employees
    ];

    //mengembalikan data dan status code 200
    return response()->json($data, 200);
}

//membuat method store
public function store(Request $request)
{
    // Membuat validasi
    $validatedData = $request->validate([
        'name' => 'required',
        'gender' => 'required',
        'phone' => 'numeric|required',
        'addres' => 'required',
        'email' => 'email|required',
        'status' => 'required',
        'hired_on' => 'date|required'
    ]);

    //menggunakan model pegawai untuk insert data
    $employees = Employees::create($validatedData);

    $data = [
        'message' => "pegawai is creates succesfully",
        'data' => $employees,
    ];

    //mengembalikan data json dan kode 201
    return response()->json($data, 201);
}

public function update($id, Request $request)
{
    //menangkap id dari parameter
    $employees = Employees::find($id);

    // jika data yang dicari tidak ada, kirim kode 404
    if(!$employees){
        $data = [
            "message" => "Data not found"
        ];

        return response()->json($data, 404);
    }

    $employees->update([
        'name' => $request->name ?? $employees->name,
        'gender' => $request->gender ?? $employees->gender,
        'phone' => $request->phone ?? $employees->phone,
        'addres' => $request->addres ?? $employees->addres,
        'email' => $request->email ?? $employees->email,
        'status' => $request->status ?? $employees->status,
        'hired_on' => $request->hired_on ?? $employees->hired_on,
    ]);


    //menyimpan data yang telah diubah
    $employees->save();

    $data = [
        'message' => 'Data berhasil Diubah',
        'data' => $employees,
    ];
    return response()->json($data, 200);
}

public function destroy($id)
    {
        //Mencari data pegawai berdasarkan ID
        $employees = Employees::find($id);

        //Jika data yang dicari tidak ada, kirim kode 404
        if (!$employees) {
            return response()->json(['message' => 'Pegawai not found'], 404);
        }

        //Menghapus data pegawai
        $employees->delete();

        return response()->json(['message' => 'Pegawai is deleted'], 200);
    }


    public function search(Request $request)
{
    // Validasi input pencarian
    $request->validate([
        'query' => 'required|string',
    ]);

    // Mendapatkan query pencarian dari request
    $searchQuery = $request->input('query');

    // Melakukan pencarian berdasarkan nama
    $results = Employees::where('name', 'like', '%' . $searchQuery . '%')->get();

    // Jika data yang ditemukan kosong, kirim kode 404
    if ($results->isEmpty()) {
        $data = [
            "message" => "No matching data found"
        ];

        return response()->json($data, 404);
    }

    // Menyiapkan data hasil pencarian
    $data = [
        'message' => "Search results for '$searchQuery'",
        'data' => $results,
    ];

    // Mengembalikan data dan status code 200
    return response()->json($data, 200);
}
    
}