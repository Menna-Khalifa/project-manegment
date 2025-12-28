<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ProjectTeamController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:project_teams_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_project_team', ['only' => ['create', 'store', 'bulkAssign']]);
        $this->middleware('check.permission:edit_project_team', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:show_project_team', ['only' => ['show']]);
        $this->middleware('check.permission:delete_project_team', ['only' => ['destroy']]);
        $this->middleware('check.permission:transfer_project_team', ['only' => ['transfer']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = ProjectTeam::with(['project', 'user']);

            // Filter by project
            if ($request->filled('project_id')) {
                $query->byProject($request->project_id);
            }

            // Filter by user
            if ($request->filled('user_id')) {
                $query->byUser($request->user_id);
            }

            // Search in project name or user name
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('project', function ($subQ) use ($request) {
                        $subQ->where('name', 'like', '%' . $request->search . '%');
                    })->orWhereHas('user', function ($subQ) use ($request) {
                        $subQ->where('name', 'like', '%' . $request->search . '%');
                    });
                });
            }

            // Sort
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortBy, $sortDirection);

            $projectTeams = $query->paginate(50);
            $projects = Project::all(['id', 'name']);
            $users = User::user()->select(['id', 'name', 'phone'])->get();

            return view('dashboard.project_teams.index', compact('projectTeams', 'projects', 'users'));
        } catch (\Exception $e) {
            Log::error('Error fetching project teams: ' . $e->getMessage());

            notify('Error fetching project teams: ' . $e->getMessage(), 'error');

            return back()->with('error', 'حدث خطأ أثناء استرداد فرق المشاريع.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $projects = Project::active()->get(['id', 'name']);
            $users = User::all(['id', 'name']);

            return view('dashboard.project_teams.create', compact('projects', 'users'));
        } catch (\Exception $e) {
            Log::error('Error loading project team create form: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل نموذج إنشاء فريق المشروع.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'project_id' => 'required|exists:projects,id',
                'user_id' => 'required|exists:users,id',
                'is_lead' => 'nullable', // Remove boolean validation
            ]);

            // Check if user is already assigned to this project
            $existingAssignment = ProjectTeam::where('project_id', $validatedData['project_id'])
                ->where('user_id', $validatedData['user_id'])
                ->first();

            if ($existingAssignment) {
                notify('This user is already added to this project.', 'error');
                return back()->withInput();
            }

            // Convert is_lead to boolean properly
            $isLead = $request->has('is_lead') && $request->is_lead == '1';

            $currentLeaderName = null;

            // If setting as leader, remove current leader first
            if ($isLead) {
                $currentLeader = ProjectTeam::where('project_id', $validatedData['project_id'])
                    ->where('is_lead', true)
                    ->with('user')
                    ->first();

                if ($currentLeader) {
                    $currentLeader->update(['is_lead' => false]);
                    $currentLeaderName = $currentLeader->user->name;
                }
            }

            // Create the new project team member
            $projectTeam = ProjectTeam::create([
                'project_id' => $validatedData['project_id'],
                'user_id' => $validatedData['user_id'],
                'is_lead' => $isLead, // Use the properly converted boolean
            ]);

            // Prepare success message
            $user = User::find($validatedData['user_id']);
            $project = Project::find($validatedData['project_id']);

            $message = 'The member ' . $user->name . ' was added to ' . $project->name . ' successfully.';

            if ($isLead) {
                $message .= ' They have been set as the project leader.';

                if ($currentLeaderName) {
                    $message .= ' ' . $currentLeaderName . ' is no longer the leader.';
                }
            }

            notify($message, 'success');

            return redirect()->route('project-teams.show', $projectTeam);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating project team: ' . $e->getMessage());
            notify('An error occurred while adding the member to the project team.', 'error');
            return back()->withInput();
        }
    }

    // Add this method to get current leader via AJAX
    public function getLeader(Request $request)
    {
        try {
            $projectId = $request->get('project_id');


            if (!$projectId) {
                return response()->json(['leader' => null]);
            }

            $leader = ProjectTeam::where('project_id', $projectId)
                ->where('is_lead', true)
                ->with('user:id,name')
                ->first();

            return response()->json([
                'leader' => $leader ? $leader->user : null
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch leader'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectTeam $projectTeam)
    {
        try {
            $projectTeam->load(['project', 'user']);

            return view('dashboard.project_teams.show', compact('projectTeam'));
        } catch (\Exception $e) {
            Log::error('Error loading project team details: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل تفاصيل فريق المشروع.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectTeam $projectTeam)
    {
        try {
            $projects = Project::all(['id', 'name']);
            $users = User::all(['id', 'name']);

            return view('dashboard.project_teams.edit', compact('projectTeam', 'projects', 'users'));
        } catch (\Exception $e) {
            Log::error('Error loading project team edit form: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل نموذج تحرير فريق المشروع.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectTeam $projectTeam)
    {
        try {
            $validatedData = $request->validate([
                'project_id' => 'required|exists:projects,id',
                'user_id' => 'required|exists:users,id',
                'is_lead' => 'nullable',
            ]);

            // Store original values for comparison
            $originalProjectId = $projectTeam->project_id;
            $originalUserId = $projectTeam->user_id;
            $originalIsLead = $projectTeam->is_lead;

            // Convert is_lead to boolean properly
            $isLead = $request->has('is_lead') && $request->is_lead == '1';

            // Check if user is already assigned to the new project (if project is changing)
            if ($validatedData['project_id'] != $originalProjectId || $validatedData['user_id'] != $originalUserId) {
                $existingAssignment = ProjectTeam::where('project_id', $validatedData['project_id'])
                    ->where('user_id', $validatedData['user_id'])
                    ->where('id', '!=', $projectTeam->id) // Exclude current record
                    ->first();

                if ($existingAssignment) {
                    notify('This user is already assigned to this project.', 'error');
                    return back()->withInput();
                }
            }

            $currentLeaderName = null;
            $previousLeaderName = null;

            // Handle leadership changes
            if ($isLead) {
                // If setting as leader, remove current leader from the target project
                $currentLeader = ProjectTeam::where('project_id', $validatedData['project_id'])
                    ->where('is_lead', true)
                    ->where('id', '!=', $projectTeam->id) // Exclude current record
                    ->with('user')
                    ->first();

                if ($currentLeader) {
                    $currentLeader->update(['is_lead' => false]);
                    $currentLeaderName = $currentLeader->user->name;
                }
            } else {
                // If removing leadership, check if this was a leader
                if ($originalIsLead) {
                    $user = User::find($originalUserId);
                    $previousLeaderName = $user->name;
                }
            }

            // Update the project team assignment
            $projectTeam->update([
                'project_id' => $validatedData['project_id'],
                'user_id' => $validatedData['user_id'],
                'is_lead' => $isLead,
            ]);

            // Prepare success message
            $user = User::find($validatedData['user_id']);
            $project = Project::find($validatedData['project_id']);

            $message = "Assignment updated successfully. ";

            // Add details about what changed
            $changes = [];

            if ($originalProjectId != $validatedData['project_id']) {
                $oldProject = Project::find($originalProjectId);
                $changes[] = "moved from '{$oldProject->name}' to '{$project->name}'";
            }

            if ($originalUserId != $validatedData['user_id']) {
                $oldUser = User::find($originalUserId);
                $changes[] = "user changed from '{$oldUser->name}' to '{$user->name}'";
            }

            if ($originalIsLead != $isLead) {
                if ($isLead) {
                    $changes[] = "promoted to project leader";
                    if ($currentLeaderName) {
                        $changes[] = "replaced {$currentLeaderName} as leader";
                    }
                } else {
                    $changes[] = "removed from leadership";
                }
            }

            if (!empty($changes)) {
                $message .= implode(', ', array_map('ucfirst', $changes)) . ".";
            } else {
                $message .= "No changes were made.";
            }

            notify($message, 'success');

            return redirect()->route('project-teams.show', $projectTeam);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating project team: ' . $e->getMessage());
            notify('An error occurred while updating the assignment.', 'error');
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectTeam $projectTeam)
    {
        try {
            $projectTeam->delete();

            notify('The member was successfully removed from the project team.', 'success');

            return redirect()->route('project-teams.index');
        } catch (\Exception $e) {
            Log::error('Error deleting project team: ' . $e->getMessage());

            notify('An error occurred while removing the member from the project team.', 'error');

            return back();
        }
    }

    /**
     * Bulk assign users to a project
     */
    public function bulkAssign(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'project_id' => 'required|exists:projects,id',
                'user_ids' => 'required|array|min:1',
                'user_ids.*' => 'exists:users,id',
                'project_lead_id' => 'nullable|exists:users,id|in:' . implode(',', $request->user_ids ?? []),
            ]);

            $projectId = $validatedData['project_id'];
            $userIds = $validatedData['user_ids'];
            $projectLeadId = $validatedData['project_lead_id'] ?? null;

            // Validate that lead is among selected users
            if ($projectLeadId && !in_array($projectLeadId, $userIds)) {
                notify('القائد المحدد يجب أن يكون من ضمن المستخدمين المختارين.', 'error');
                return back()->withInput();
            }

            // Get existing assignments
            $existingAssignments = ProjectTeam::where('project_id', $projectId)
                ->whereIn('user_id', $userIds)
                ->pluck('user_id')
                ->toArray();

            // Filter out already assigned users
            $newUserIds = array_diff($userIds, $existingAssignments);

            // If lead is selected and already exists, update their is_lead status
            if ($projectLeadId && in_array($projectLeadId, $existingAssignments)) {
                // Remove current lead from this project
                ProjectTeam::where('project_id', $projectId)
                    ->where('is_lead', true)
                    ->update(['is_lead' => false]);

                // Set new lead
                ProjectTeam::where('project_id', $projectId)
                    ->where('user_id', $projectLeadId)
                    ->update(['is_lead' => true]);
            }

            // Create new assignments
            $assignments = [];
            foreach ($newUserIds as $userId) {
                $assignments[] = [
                    'project_id' => $projectId,
                    'user_id' => $userId,
                    'is_lead' => ($projectLeadId && $userId == $projectLeadId),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($assignments)) {
                // If there's a new lead among new assignments, remove existing leads first
                if ($projectLeadId && in_array($projectLeadId, $newUserIds)) {
                    ProjectTeam::where('project_id', $projectId)
                        ->where('is_lead', true)
                        ->update(['is_lead' => false]);
                }

                ProjectTeam::insert($assignments);

                $message = 'تم إضافة ' . count($assignments) . ' عضو جديد إلى فريق المشروع.';

                if ($projectLeadId) {
                    $leadUser = \App\Models\User::find($projectLeadId);
                    $message .= ' تم تعيين ' . $leadUser->name . ' كقائد للمشروع.';
                }
            } else {
                $message = 'جميع المستخدمين المحددين مضافين بالفعل إلى هذا المشروع.';

                // Still update lead if specified
                if ($projectLeadId) {
                    ProjectTeam::where('project_id', $projectId)
                        ->where('is_lead', true)
                        ->update(['is_lead' => false]);

                    ProjectTeam::where('project_id', $projectId)
                        ->where('user_id', $projectLeadId)
                        ->update(['is_lead' => true]);

                    $leadUser = \App\Models\User::find($projectLeadId);
                    $message .= ' تم تعيين ' . $leadUser->name . ' كقائد للمشروع.';
                }
            }

            if (!empty($existingAssignments)) {
                $message .= ' ' . count($existingAssignments) . ' مستخدم مضاف بالفعل.';
            }

            notify($message, 'success');
            return back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error bulk assigning project team: ' . $e->getMessage());
            notify('An error occurred during the bulk addition of the project team.', 'error');
            return back();
        }
    }

    public function transfer(Request $request, ProjectTeam $projectTeam)
    {
        try {
            $validatedData = $request->validate([
                'new_project_id' => 'required|exists:projects,id|different:current_project_id',
            ]);

            $newProjectId = $validatedData['new_project_id'];
            $userId = $projectTeam->user_id;
            $currentProjectId = $projectTeam->project_id;

            // التأكد من أن المشروع الجديد مختلف عن المشروع الحالي
            if ($newProjectId == $currentProjectId) {
                notify('Cannot transfer user to the same project.', 'error');
                return back();
            }

            // التحقق من أن المستخدم غير موجود في المشروع الجديد
            $existingInNewProject = ProjectTeam::where('project_id', $newProjectId)
                ->where('user_id', $userId)
                ->first();

            if ($existingInNewProject) {
                notify('This user is already assigned to the target project.', 'error');
                return back();
            }

            DB::beginTransaction();

            try {
                // حفظ معلومات المستخدم والمشاريع للرسالة
                $userName = $projectTeam->user->name;
                $currentProjectName = $projectTeam->project->name;
                $newProject = Project::find($newProjectId);
                $newProjectName = $newProject->name;

                // تحديث معرف المشروع في السجل الحالي
                $projectTeam->update([
                    'project_id' => $newProjectId,
                    'updated_at' => now()
                ]);

                DB::commit();

                notify("User '{$userName}' has been successfully transferred from '{$currentProjectName}' to '{$newProjectName}'.", 'success');

                return redirect()->route('project-teams.index');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error transferring project team member: ' . $e->getMessage());
            notify('An error occurred while transferring the team member.', 'error');
            return back();
        }
    }
}
