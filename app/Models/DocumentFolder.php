<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentFolder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'intern_id',
        'name',
        'color',
        'description',
        'parent_folder_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the intern that owns the folder
     */
    public function intern()
    {
        return $this->belongsTo(Intern::class);
    }

    /**
     * Get the parent folder
     */
    public function parentFolder()
    {
        return $this->belongsTo(DocumentFolder::class, 'parent_folder_id');
    }

    /**
     * Get child folders
     */
    public function childFolders()
    {
        return $this->hasMany(DocumentFolder::class, 'parent_folder_id');
    }

    /**
     * Get documents in this folder
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'folder_id');
    }

    /**
     * Get all documents including in subfolders
     */
    public function allDocuments()
    {
        $documents = $this->documents;

        foreach ($this->childFolders as $folder) {
            $documents = $documents->concat($folder->allDocuments());
        }

        return $documents;
    }
}
