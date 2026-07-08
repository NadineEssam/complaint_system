<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    const CREATED_AT = 'entryDate';
    const UPDATED_AT = 'UpdateDate';

    protected $table = 'sfdcomplaints';

    protected $primaryKey = 'ComplaintID';

    public $timestamps = true;

    protected $fillable = [
        'ComplaintTitle',
        'ComplaintText',
        'ComplaintType',
        'ComplainerName',
        'ComplainerEmail',
        'ComplainerPhone',
        'ComplainerGovernorate',
        'ComplaintDate',
        'ComplaintStatus',
        'ComplaintNationalID',
        'ComplainerGender',
        'ComplaintSources',
        'RequestType',
        'office',
        'sector_id',
        'department',
        'ComplaintGovernorate',
        'fk_close_reason_id',
        'fk_close_reason_classify_id',
        'created_by',
        'updated_by',
        'UpdateUser',
        'username',
        'complaint_type',
        'ComplaintProjectType',
        'parent_id',
        'valid',


    ];

    protected $attributes = [
        'fk_close_reason_id' => 0,
        'fk_close_reason_classify_id' => 0,
        'ComplaintStatus' => 3,
        'valid'=> 1, 
    ];
    protected static function booted()
    {
        static::addGlobalScope('valid', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->where('sfdcomplaints.valid', 1);
        });
    }
    public static function withInvalid()
    {
        return static::withoutGlobalScope('valid');
    }

    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class, 'complaint_id', 'ComplaintID');
    }

    public function status()
    {
        return $this->belongsTo(
            CompStatus::class,
            'ComplaintStatus',
            'statusID'
        );
    }

    public function closeReason()
    {
        return $this->belongsTo(
            CompCloseReason::class,
            'fk_close_reason_id',
            'close_reason_ID'
        );
    }




    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function sources()
    {
        return $this->belongsToMany(
            ComSource::class,
            'complaint_sources',
            'complaint_id',
            'comsource_id'
        );
    }
    public function requestType()
    {
        return $this->belongsTo(
            RequestType::class,
            'RequestType',
            'requesttypeid'
        );
    }

    public function complaintType()
    {
        return $this->belongsTo(
            ComplaintType::class,
            'ComplaintType',
            'comtypeid'
        );
    }

    public function sector()
    {
        return $this->belongsTo(
            Sector::class,
            'sector_id',
            'sec_id'
        );
    }


    public function departmentInfo()
    {
        return $this->belongsTo(
            Department::class,
            'department',
            'dep_id'
        );
    }

    public function gov()
    {
        return $this->belongsTo(
            Gov::class,
            'ComplaintGovernorate',
            'GOVT_CODE'
        );
    }

    public function complainerGov()
    {
        return $this->belongsTo(Gov::class, 'ComplainerGovernorate', 'GOVT_CODE');
    }

    public function office_info()
    {
        return $this->belongsTo(
            Office::class,
            'office',
            'ID'
        );
    }
    public function projectTypes()
    {
        return $this->belongsTo(
            ProjectType::class,
            'ComplaintProjectType',
            'ID'
        );
    }


    public function parent()
    {
        return $this->belongsTo(
            Complaint::class,
            'parent_id',
            'ComplaintID'
        );
    }

    public function children()
    {
        return $this->hasMany(
            Complaint::class,
            'parent_id',
            'ComplaintID'
        );
    }

    /**
     * Walk all the way up the parent chain to find the true root
     * (original) complaint, regardless of how many levels deep this
     * complaint is nested.
     */
    public function getRootAttribute(): self
    {
        $node = $this;
        while ($node->parent_id) {
            $node = $node->parent;
        }
        return $node;
    }

    /**
     * Every descendant of the given root, at any depth (children,
     * grandchildren, etc.) — not just direct children.
     */
    public static function descendantsOf(int $rootId): \Illuminate\Support\Collection
    {
        $all = collect();
        $queue = [$rootId];

        while (!empty($queue)) {
            $children = static::whereIn('parent_id', $queue)->get();
            if ($children->isEmpty()) {
                break;
            }
            $all = $all->merge($children);
            $queue = $children->pluck('ComplaintID')->all();
        }

        return $all;
    }

    /**
     * Whether this complaint belongs to a duplicate family at all —
     * either as the original (has descendants) or as a duplicate
     * itself (has a parent somewhere up the chain).
     */
    public function getIsDuplicatedAttribute(): bool
    {
        return $this->duplicates_count > 0;
    }

    /**
     * Total number of complaints in this family tree besides the
     * root itself, counted from the true root regardless of which
     * level (parent, child, grandchild...) this record sits at.
     */
    public function getDuplicatesCountAttribute(): int
    {
        $root = $this->root;

        return static::descendantsOf($root->ComplaintID)->count();
    }

    /**
     * The first complaint anywhere in this family (root, children,
     * sub-children...) that is still "open" (status 1 or 3, i.e. not
     * yet closed). Returns null if every complaint in the family has
     * been closed — meaning a new duplicate is allowed to be added.
     */
    public function getOpenFamilyMemberAttribute(): ?self
    {
        $root = $this->root;

        $family = Complaint::descendantsOf($root->ComplaintID)->push($root);

        return $family->first(function ($c) {
            return in_array($c->ComplaintStatus, [1, 3]);
        });
    }
}
