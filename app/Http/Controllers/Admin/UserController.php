<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BanksDetail;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Laratrust\Helper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Expertise;
use App\Models\Country;
use App\Models\InvestmentRange;
use App\Models\Operator;
use App\Models\Sector;
use App\Models\Staff;
use App\Models\UserType;
use App\Notifications\SendPasswordToNewStaff;
use Cloudder;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

use DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = \Auth::user();

        // $user = User::with('userable.staff.user')->where('id', $user->id)->first();
        // dd($user->userable->staff->toArray());

        if ($request->ajax()) {

            $user = User::with('userable.staff.user')->where('id', $user->id)->first();
            $data = $user->userable->staff;

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // if(isset($row->employee->id)){
                    $actionBtn = '
                        <a href="' . route('staff-profile', $row->id) . '" class="btn btn-sm btn-success btn-icon waves-effect waves-light"><i class="mdi mdi-lead-pencil"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('staffs.index');
    }
    // roles permissions assignments
    public function userRolesPermissionList(Request $request)
    {

        $modelsKeys = array_keys(Config::get('laratrust.user_models'));
        $modelKey = $request->get('model') ?? $modelsKeys[0] ?? null;
        //dd(User::with(['roles','permissions'])->get()->toArray());
        if ($request->ajax()) {
            $data = User::with(['roles', 'permissions'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <a class="btn btn-sm btn-success btn-icon waves-effect waves-light" href="' . route("edit-with-role-permissions", ['id' => $row->id]) . '"><i class="mdi mdi-lead-pencil"></i></a>
                    ';

                    return $actionBtn;
                })
                ->addColumn('roles', function ($row) {
                    $count = ($row->roles->count());
                    return $count;
                })
                ->addColumn('permissions', function ($row) {
                    $count = ($row->permissions->count());
                    return $count;
                })
                ->rawColumns(['action', 'roles', 'permissions'])
                ->make(true);
        }

        return view('role_permissions_assignment.index', [
            'models' => $modelsKeys,
            'modelKey' => $modelKey,
        ]);
    }

    public function editUserRolesPermissions(Request $request, $id)
    {
        // dd(\Auth::user()->roles());
        if (\Auth::user()->hasRole('admin')) {
            $user = User::query()
                ->with(['roles:id,name', 'permissions:id,name'])
                ->findOrFail($id);
            $roles = Role::orderBy('name')->get(['id', 'name', 'display_name', 'description'])
                ->map(function ($role) use ($user) {
                    $role->assigned = $user->roles
                        ->pluck('id')
                        ->contains($role->id);
                    $role->isRemovable = Helper::roleIsRemovable($role);

                    return $role;
                });
            $permissions = Permission::orderBy('name')
                ->get(['id', 'name', 'display_name', 'description'])
                ->map(function ($permission) use ($user) {
                    $permission->assigned = $user->permissions
                        ->pluck('id')
                        ->contains($permission->id);

                    return $permission;
                });


            $data['roles'] = $roles;
            $data['permissions'] = $permissions;
            $data['user'] = $user;
            // dd($data);
            return view('role_permissions_assignment.edit', $data);
        }
    }

    public function updateUserRolesPermissions(Request $request, $id)
    {
        $modelKey = 'users';
        $userModel = Config::get('laratrust.user_models')[$modelKey] ?? null;

        if (!$userModel) {
            //'Model was not specified in the request';
            //return redirect()->back()->with('error','Unfortunately not able to update the role assignment');
        }

        $user = $userModel::findOrFail($id);
        $user->syncRoles($request->get('roles') ?? []);
        $user->syncPermissions($request->get('permissions') ?? []);

        return redirect()->back()->with('success', 'Your details for the user have been successfully updated!');;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = \Auth::user();
        $user = User::with('userable.staff.user')->where('id', $user->id)->first();
        return view('staffs.add_new_profile', ["user" => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "fullName" => "required|min:5",
            "phone" => "required",
            "reportsTo" => "required",
            "username" => "required|alpha_dash|unique:users,username,NULL,NULL,deleted_at,NULL|min:5",
            "email" => "required|email|unique:users,email,NULL,NULL,deleted_at,NULL",
        ]);

        $loggedInUser = \Auth::user();

        $staff = new Staff();

        if ($loggedInUser->userable_type == "App\Models\Operator") {
            $staffable = Operator::find($loggedInUser->userable_id);
        } else {
            $staffable = User::find($loggedInUser->id);
        }

        $staff->reportsTo = $request->reportsTo;
        $staffable->staff()->save($staff);

        $user = new User();
        $user->name = $request->fullName;
        $user->email = $request->email;
        $user->username = strtolower($request->username);
        $user->cityId = $loggedInUser->cityId;
        $user->branchId = 1;
        $user->phone = $request->phone;
        $user->status = 1;
        $randString = Str::random(10);
        $user->passwordText = $randString;
        $user->password = Hash::make($randString);
        $user->credits = env("OPERATOR_CREDITS_FREE");

        if ($request->hasFile("photo")) {
            $imgOptions = ['folder' => 'guide_profile', 'format' => 'webp'];
            $cloudder = Cloudder::upload($request->file('photo')->getRealPath(), null, $imgOptions);
            $result = $cloudder->getResult();
            if (isset($result["public_id"])) {
                $user->profilePic = $result["public_id"];
            }
        }

        $staff->user()->save($user);

        $user->attachRole("staff");

        $data = [
            'email' => $user->email,
            'password' => $user->passwordText,
        ];
        $user->notify(new SendPasswordToNewStaff($data));

        return redirect(route('staffs.index'))->with('success', 'Staff created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $user = \Auth::user();
        $user_info = Staff::with(['user', 'reportsToUser'])->where('id', $id)->first();
        $users = User::with('userable.staff.user')->where('id', $user->id)->first();
        // dd($user_info->toArray());
        $data = [
            'user_info' => $user_info,
            'user' => $users,
        ];

        return view('staffs.edit_profile', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $logged_user = User::findorfail($request->user_id);
        $loggedInUser = \Auth::user();
        // dd($logged_user->toArray());
        if ($request->password) {
            $request->validate([
                'password' => 'required | min:8 | confirmed',
            ]);
            $logged_user->passwordText = $request->password;
            $logged_user->password = Hash::make($request->password);
            $logged_user->save();
        } else {
            $request->validate([
                "fullName" => "required|min:5",
                "phone" => "required",
                "reportsTo" => "required",
                "username" => "required|alpha_dash|min:5|unique:users,username,NULL,NULL,deleted_at,NULL" . $logged_user->id,
                "email" => "required|email|unique:users,email,NULL,NULL,deleted_at,NULL" . $logged_user->id,
            ]);

            $logged_user->name = $request->fullName;
            $logged_user->email = $request->email;
            $logged_user->username = strtolower($request->username);
            $logged_user->cityId = $loggedInUser->cityId;
            $logged_user->branchId = $loggedInUser->branchId;
            $logged_user->phone = $request->phone;
            $logged_user->status = 1;
            $logged_user->save();
        }
        if ($request->hasFile("photo")) {
            $imgOptions = ['folder' => 'guide_profile', 'format' => 'webp'];
            $cloudder = Cloudder::upload($request->file('photo')->getRealPath(), null, $imgOptions);
            $result = $cloudder->getResult();
            if (isset($result["public_id"])) {
                $logged_user->profilePic = $result["public_id"];
                $logged_user->save();
            }
        }
        return redirect()->route('staffs.index')->with('success', 'Staff updated successfully');
    }

    public function uploadCropImage(Request $request)
    {
        $data = $request->all();
        $folderPath = public_path() . '/files/user_profiles/';

        $image_parts = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $imageName = uniqid() . '.png';
        $imageFullPath = $folderPath . $imageName;
        file_put_contents($imageFullPath, $image_base64);
        $exist_record = User::where('id', $request->id)->first();
        $data['profile_picture'] = $imageName;

        if ($exist_record) {
            $exist_record->update($data);
        } else {
            User::create($data);
        }
        return response()->json(['success' => 'Crop Image Uploaded Successfully']);
    }

    public function restoreArchiveUser($id)
    {
        User::withTrashed()->find($id)->restore();
        return redirect(route('shareholders.index'))->with('success', 'Archive user successfully restored.');
    }

    public function restoreArchiveStaff($id)
    {
        User::withTrashed()->find($id)->restore();
        return redirect(route('staff.index'))->with('success', 'Archive user successfully restored.');
    }

    public function archivedstaff(Request $request)
    {
        if ($request->ajax()) {

            $data = User::onlyTrashed()->where('user_type_id', 4)->orderBy('id', 'DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('staff.restore', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('staff.archived_staff');
    }

    public function archivedVolunteer(Request $request)
    {
        if ($request->ajax()) {

            $data = User::onlyTrashed()->where('user_type_id', 5)->orderBy('id', 'DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('volunteers.restore', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('volunteers.archived_volunteer');
    }

    public function restoreArchiveVolunteer($id)
    {
        User::withTrashed()->find($id)->restore();
        return redirect(route('volunteers.index'))->with('success', 'Volunteer user successfully restored.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function destroy(User $user)
    {
        try {
            return $user->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function update_user_profile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->request_type == 'personal_info') {
            // dd($request->all());
            $request->validate([
                'name' => 'required',
                'username' => 'required',
                'phone' => 'required',
                'email' => ['required', Rule::unique('users')->ignore($user->id)],
                'cityId' => 'required',
            ]);

            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'phone' => $request->phone,
                'email' => $request->email,
                'cityId' => $request->cityId,
            ]);
            // dd($request->all(), $user->toArray());
        }
        if ($request->request_type == 'change_password') {
            $request->validate([
                'oldPassword' => 'required',
                'nPassword' => 'required|min:8|required_with:cPassword|same:cPassword'
            ]);

            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }
        if ($request->request_type == 'bussiness_info') {
        }

        return redirect()->back()->with('success', 'User details updated successfully');
    }

    public function open_bank_modal()
    {
        // dd("hello");
        return view('profile.bank_detail_modal');
    }

    public function add_bank_details(Request $request)
    {
        $request->validate([
            'accountNo' => 'required',
            'accountTitle' => 'required',
            'bankName' => 'required',
            'bankAddress' => 'required',
            'bankPhone' => 'required',
            'swiftCode' => 'required',
            'IBAN' => 'required',
        ]);

        BanksDetail::create($request->all());
        return redirect()->back()->with('success', 'Bank Detail entered');
    }

    public function edit_bank_detail($id)
    {
        $bank_detail = BanksDetail::findOrFail($id);
        // dd($bank_detail->toArray());
        return view('profile.bank_detail_modal', ['bank_detail' => $bank_detail]);
    }

    public function update_bank_details(Request $request, $id)
    {
        $bank_detail = BanksDetail::findOrFail($id);
        $request->validate([
            'accountNo' => 'required',
            'accountTitle' => 'required',
            'bankName' => 'required',
            'bankAddress' => 'required',
            'bankPhone' => 'required',
            'swiftCode' => 'required',
            'IBAN' => 'required',
        ]);

        $bank_detail->update($request->all());
        return redirect()->back()->with('success', 'Bank Detail updated');
    }
}
