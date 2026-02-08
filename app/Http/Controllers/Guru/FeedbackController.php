<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FeedbackController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = Feedback::with('guru:id,nip,nama_lengkap')
            ->where('id_guru', auth()->user()->guru->id)
            ->orWhere('id_guru', null)
            ->get();

            return response()->json([
                'data' => $data
            ]);
        }

        return view('guru.tanggapan.index');
    }

    public function store(Request $request)
    {
        $input = $request->only('feedback_deskripsi', 'status');
        $input['id_guru'] = auth()->user()->guru->id;

        try{
            Feedback::create($input);

            $message = 'Data tanggapan berhasil dibuat';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }

        return redirect()->route('guru.tanggapan.index')->with('message', $message);
    }

    public function edit(Feedback $feedback)
    {
        return response()->json([
            'data' => $feedback
        ]);
    }

    public function update(Feedback $feedback, Request $request)
    {
        $input = $request->only('feedback_deskripsi', 'status');

        try{
            $feedback->update($input);

            $message = 'Data tanggapan berhasil diperbarui';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }

        return redirect()->route('guru.tanggapan.index')->with('message', $message);
    }

    public function destroy(Feedback $feedback)
    {
        try{
            $feedback->delete();
            $message = 'Data tanggapan berhasil dihapus';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }
        
        return response()->json([
            'message' => $message
        ]);
    }

    public function import(Request $request)
    {
        $data = $request->input('data');

        if (!is_array($data) || empty($data)) {
            return response()->json(['status' => 400, 'message' => 'Data tidak valid!']);
        }

        $response = new StreamedResponse(function () use ($data) {
            $insertedCount = 0;
            $updatedCount = 0;

            foreach ($data as $index => $item) {
                if (!isset($item['feedback_deskripsi']) || !isset($item['status'])) {
                    echo json_encode(['status' => 400, 'message' => 'Format data tidak valid!']);
                    ob_flush();
                    flush();
                    return;
                }

                $record = Feedback::Create([
                    'feedback_deskripsi' => $item['feedback_deskripsi'],
                    'status' => $item['status'],
                    'id_guru' => auth()->user()->guru->id
                ]);

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
