<?php

namespace App\Repositories;

use App\Models\Document;
use Illuminate\Support\Str;

class DocumentRepository implements DocumentRepositoryInterface
{
    public function getDocumentsByStudentId(string $studentId)
    {
        return Document::where('student_id', $studentId)->get();
    }

    public function findById(string $id)
    {
        return Document::find($id);
    }

    public function create(array $data)
    {
        $data['id'] = $data['id'] ?? Str::uuid();
        return Document::create($data);
    }

    public function update(string $id, array $data)
    {
        $document = Document::find($id);
        if ($document) {
            $document->update($data);
        }
        return $document;
    }

    public function delete(string $id)
    {
        $document = Document::find($id);
        if ($document) {
            return $document->delete();
        }
        return false;
    }
}