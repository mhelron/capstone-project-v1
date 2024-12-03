<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $database, $users, $archived_users;

    public function __construct(Database $database){
        $this->database = $database;
        $this->users = 'users';
        $this->archived_users = 'archived_users';
    }

    public function index(){
        $users = $this->database->getReference('users')->getValue();
        $users = is_array($users) ? $users : [];

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.users.index', compact('users', 'isExpanded'));
    }

    public function create(){
        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.users.create', compact( 'isExpanded'));
    }

    public function store(Request $request){

        // Validations
        $validatedData = $request->validate([
            'user_role' => 'required',
            'first_name' => 'required|regex:/^[a-zA-Z\s\-]+$/',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email',
            'password' => [
                'required',
                'min:8',
                /*
                (?=.*[A-Z]) para sa uppercase
                (?=.*[0-9]) para sa number
                (?=.*[!@#$%^&*]) para sa special char
                */
                'regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/',
                'confirmed',
            ],
            'password_confirmation' => 'required_with:password',
        ], [ // Custom Error Message
            'user_role.required' => 'Please select a role.',
            'first_name.required' => 'The first name is required.',
            'first_name.regex' => 'The first name must only contain letters, spaces, or hyphens.',
            'last_name.required' => 'The last name is required.',
            'last_name.regex' => 'The last name must only contain letters and spaces.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'The password must contain at least one uppercase letter, one number, and one special character.',
            'password.confirmed' => 'The password confirmation does not match.',
            // By pass lang para mag show yung confirm error password sa field nito.
            'password_confirmation.required_with' => 'The password confirmation does not match.'
        ]);
        
        // Checking if yung email is existing
        try { 
            $firebaseAuth = app('firebase.auth');
            $existingUser = $firebaseAuth->getUserByEmail($validatedData['email']);
    
            if ($existingUser) {
                return redirect('admin/users/add-user')->withErrors([
                    'email' => 'This email is already in use.'
                ])->withInput();
            }
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {

        } catch (\Exception $e) {
            return redirect('admin/users/add-user')->with('status', 'Error: ' . $e->getMessage())->withInput();
        }
    
        try {
            $firebaseUser = $firebaseAuth->createUser([
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'displayName' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
            ]);
        
            $hashedPassword = Hash::make($validatedData['password']);
            
            $userData = [
                'user_role' => $validatedData['user_role'],
                'fname' => $validatedData['first_name'],
                'lname' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => $hashedPassword,
                'firebase_uid' => $firebaseUser->uid,
            ];
            
            $postRef = $this->database->getReference($this->users)->push($userData);
            
            if ($postRef) {
                $user = Session::get('firebase_user');
                
                Log::info('Activity Log', [
                    'user' => $user->email,
                    'action' => 'Added a new user: ' . $validatedData['email'] . '.'
                ]);
                return redirect('admin/users')->with('status', 'User Added Successfully');
            } else {
                return redirect('admin/users')->with('status', 'User Not Added');
            }
        } catch (\Exception $e) {
            return redirect('admin/users/add-user')->with('status', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id){
        $key = $id;
        
        $editdata = $this->database->getReference($this->users)->getChild($key)->getValue();

        $isExpanded = session()->get('sidebar_is_expanded', true);
        if($editdata){
            return view('admin.users.edit', compact('editdata', 'key', 'isExpanded'));
        } else {
            return redirect('admin/users')->with('status', 'User ID Not Found');
        }
    }

    public function update(Request $request, $id) {
        $key = $id;
    
        // Validations
        $validatedData = $request->validate([
            'user_role' => 'required',
            'first_name' => 'required|regex:/^[a-zA-Z\s\-]+$/',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email',
            'password' => [
                'nullable', // Allow the password to be optional
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/',
                'confirmed',
            ],
            'password_confirmation' => 'nullable|required_with:password',
        ], [ // Custom Error Messages
            'user_role.required' => 'Please select a role.',
            'first_name.required' => 'The first name is required.',
            'first_name.regex' => 'The first name must only contain letters, spaces, or hyphens.',
            'last_name.required' => 'The last name is required.',
            'last_name.regex' => 'The last name must only contain letters and spaces.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'The password must contain at least one uppercase letter, one number, and one special character.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password_confirmation.required_with' => 'The password confirmation does not match.',
        ]);
    
        
        $existingUser = $this->database->getReference($this->users . '/' . $key)->getValue();
    
        if (!$existingUser || !isset($existingUser['firebase_uid'])) {
            return redirect('admin/users')->with('status', 'User ID or Firebase UID not found.');
        }

         // Track what changed
        $changes = [];

        // Compare old and new values
        if ($existingUser['fname'] . ' ' . $existingUser['lname'] !== $validatedData['first_name'] . ' ' . $validatedData['last_name']) {
            $changes[] = "name from '{$existingUser['fname']} {$existingUser['lname']}' to '{$validatedData['first_name']} {$validatedData['last_name']}'";
        }

        if ($existingUser['email'] !== $validatedData['email']) {
            $changes[] = "email from '{$existingUser['email']}' to '{$validatedData['email']}'";
        }

        if ($existingUser['user_role'] !== $validatedData['user_role']) {
            $changes[] = "role from '{$existingUser['user_role']}' to '{$validatedData['user_role']}'";
        }

        if ($request->filled('password')) {
            $changes[] = "password";
        }
    
        $updateData = [
            'user_role' => $validatedData['user_role'],
            'fname' => $validatedData['first_name'],
            'lname' => $validatedData['last_name'],
            'email' => $validatedData['email'],
        ];
    
        if ($request->filled('password')) {
            $hashedPassword = Hash::make($validatedData['password']);
            $updateData['password'] = $hashedPassword;
        }
    
        $this->database->getReference($this->users. '/'.$key)->update($updateData);
    
        try {
            $firebaseAuth = app('firebase.auth');
            $firebaseUser = $firebaseAuth->getUser($existingUser['firebase_uid']);
    
            $authUpdateData = [
                'email' => $validatedData['email'],
                'displayName' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
            ];
    
            if ($request->filled('password')) {
                $authUpdateData['password'] = $validatedData['password'];
            }
    
            $firebaseAuth->updateUser($firebaseUser->uid, $authUpdateData);

            $existingUid = $existingUser['firebase_uid'];
            $currentUser = $firebaseAuth->getUser($existingUid);
            $sessionUid = Session::get('firebase_user')->uid;

            // Only log if there were changes
            if (!empty($changes)) {
                $changesText = implode(', ', $changes);
                
                if ($existingUid === $sessionUid) {
                    Session::put('firebase_user', $currentUser);
                    $user = Session::get('firebase_user');
                    Log::info('Activity Log', [
                        'user' => $user->email,
                        'action' => "Updated their own profile: Changed " . $changesText
                    ]);
                } else {
                    $editor = Session::get('firebase_user');
                    Log::info('Activity Log', [
                        'user' => $editor->email,
                        'action' => "Updated user {$validatedData['email']}: Changed " . $changesText . "."
                    ]);
                    return redirect('admin/users')->with('status', 'User Updated Successfully');
                }
            }

            return redirect('admin/users')->with('status', 'User Updated Successfully');
        } catch (\Exception $e) {
            return redirect('admin/users')->with('status', 'Error: ' . $e->getMessage())->withInput();
        }
    }
    

    public function archive($id){
        $key = $id;

        $user_data = $this->database->getReference($this->users.'/'.$key)->getValue();

        if (!$user_data) {
            return redirect('admin/users')->with('status', 'User Not Found');
        }

        $archive_data = $this->database->getReference($this->archived_users.'/'.$key)->set($user_data);

        if ($archive_data) {
            $delete_data = $this->database->getReference($this->users.'/'.$key)->remove();

            if ($delete_data) {
                try {
                    $firebaseAuth = app('firebase.auth');
                    $firebaseAuth->deleteUser($user_data['firebase_uid']);
                    
                    $user = Session::get('firebase_user');
                    Log::info('Activity Log', [
                        'user' => $user->email,
                        'action' => 'Archived user: ' . $user_data['email' . '.']
                    ]);
                    return redirect('admin/users')->with('status', 'User Archived Successfully');
                } catch (\Exception $e) {
                    return redirect('admin/users')->with('status', 'User Archived but Failed to Remove from Authentication: ' . $e->getMessage());
                }
            } else {
                return redirect('admin/users')->with('status', 'User Not Archived');
            }
        } else {
            return redirect('admin/users')->with('status', 'User Not Archived');
        }
    }
}