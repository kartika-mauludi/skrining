<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Validator;
use Exception;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $data = Guru::with('user')->get();
        // return response()->json([
        //     "data" => $data
        // ]);
        return view('admin.guru.index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nama_lengkap' => 'required',
            'nip' => 'required|unique:gurus,nip'
            
        ],[
            'name.required' =>'Usernmae tidak boleh dikosongkan',
            'nama_lengkap.required' =>'Nama lengkap tidak boleh dikosongkan',
            'nip.required' =>'NIP tidak boleh dikosongkan',
            'nip.unique' => 'NIP sudah pernah terpakai',
            'email.required' => 'email tidak boleh dikosongkan',
            'email.unique' => 'email sudah pernah didaftarkan',
            'password.required' => 'password tidak boleh dikosongkan',
            'password.min' => 'password minimal 8 character',
        ]);

        if($input->fails()){
             return response()->json([
                'status' => false,
                'errors' => $input->errors()
            ], 422);
        }

        $validate = $input->validated();

      DB::beginTransaction();

        try {
            $user = User::create([
                'name'     => $validate['name'],
                'email'    => $validate['email'],
                'password' => bcrypt($validate['password']),
                'role'     => 'guru',
            ]);

            Guru::create([
                'user_id'       => $user->id,
                'nip'           => $validate['nip'],
                'nama_lengkap'  => $validate['nama_lengkap'],
                'alamat'        => $request->filled('alamat') ? $request->alamat : '-',
                'tempat_lahir'  => $request->filled('tempat_lahir') ? $request->tempat_lahir : '-',
                'tgl_lahir'     => $request->filled('tgl_lahir') ? $request->tgl_lahir : Null,
                'no_tlp'        => $request->filled('no_tlp') ? $request->no_tlp : '-',
                'email'         => $validate['email']
            ]);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Guru berhasil ditambahkan'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Gagal menambah guru',
                'error' => $e->getMessage()
            ]);
        }
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
        $data = Guru::with('user')->find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guru $guru)
    {
        $validasi = $request->except('_token', '_method', 'password');
        $input = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$guru->user_id,
            'nama_lengkap' => 'required',
            'nip' => 'required|unique:gurus,nip,'.$guru->id
            
        ],[
            'name.required' =>'Usernmae tidak boleh dikosongkan',
            'nama_lengkap.required' =>'Nama lengkap tidak boleh dikosongkan',
            'nip.required' =>'NIP tidak boleh dikosongkan',
            'nip.unique' => 'NIP sudah pernah terpakai',
            'email.required' => 'email tidak boleh dikosongkan',
            'email.unique' => 'email sudah pernah didaftarkan',
        ]);

        if($input->fails()){
            return response()->json([
                'status' => false,
                'errors' => $input->errors()
            ], 422);
        }

        $status = 400;
        $message = 'gagal edit data';

         $validatedData = $input->validated();
         $result = $guru->update([
                'nip'           => $validatedData['nip'],
                'nama_lengkap'  => $validatedData['nama_lengkap'],
                'alamat'        => $request->filled('alamat') ? $request->alamat : '-',
                'tempat_lahir'  => $request->filled('tempat_lahir') ? $request->tempat_lahir : '-',
                'tgl_lahir'     => $request->filled('tgl_lahir') ? $request->tgl_lahir : Null,
                'no_tlp'        => $request->filled('no_tlp') ? $request->no_tlp : '-',
         ]);
         
         if($result){
            $status = 200;
            $message = 'data berhasil di update';
         }

         return response()->json([
            'status' => $status,
            'message' => $message
         ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru)
    {
        try {
        $guru->delete();
        return response()->json([
            'status' => 200,
            'message' => "Berhasil hapus user"
        ]);
        } catch (Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'Gagal menghapus user',
            'error' => $e->getMessage()
        ]);
        }
    }

    public function data(){
        $data = Guru::with('user')->get();
        return response()->json([
            "data" => $data
        ]);
    }

    public function import(Request $request, $universityId)
    {
        $data = $request->input('data');

        if (!is_array($data) || empty($data)) {
            return response()->json(['status' => 400, 'message' => 'Data tidak valid!']);
        }

        $response = new StreamedResponse(function () use ($data) {
            $insertedCount = 0;
            $updatedCount = 0;

            foreach ($data as $index => $item) {
                if (!isset($item['nip']) || !isset($item['username']) || !isset($item['nama_lengkap']) || !isset($item['alamat']) || !isset($item['tempat_lahir']) || !isset($item['tanggal_lahir'])|| !isset($item['no_wa'])) {
                    echo json_encode(['status' => 400, 'message' => 'Format data tidak valid!']);
                    ob_flush();
                    flush();
                    return;
                }

                $user = User::updateOrCreate(
                    ['email' => $item['email']],
                    ['username' => $item['username'], 'role' => 'guru', 'password' => bcrypt($item['nip']) ]
                );

                $record = Guru::updateOrCreate(
                    ['nip' => $item['nip'], 'nama_lengkap' => $item['nama_lengkap'], 'user_id' => $user->id],
                    ['email' => $item['email'],'alamat' => $item['alamat'], 'tempat_lahir' => $item['tempat_lahir'], 'tanggal_lahir' => $item['tanggal_lahir'], 'no_tlp' => $item['no_wa']]
                );

                if ($record->wasRecentlyCreated) {
                    $insertedCount++;
                } else {
                    $updatedCount++;
                }

                echo json_encode([
                    'status' => 200,
                    'message' => 'Mengimport data...',
                    'progress' => ($index + 1) . '/' . count($data),
                    'procesed' => $insertedCount + $updatedCount
                ]) . "\n";
                ob_flush();
                flush();
            }

            echo json_encode([
                'status' => 200,
                'message' => 'Import selesai!',
                'procesed' => $insertedCount + $updatedCount
            ]) . "\n";
            ob_flush();
            flush();
        });

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
