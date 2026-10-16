<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:candidate,company'],
        ];

        if ($request->role === 'candidate') {
            $rules = array_merge($rules, [
                'birth_date' => ['required', 'date'],
                'city' => ['required', 'string', 'max:255'],
                'domain' => ['required', 'string', 'max:255'],
                'education_level' => ['required', 'string', 'max:255'],
                'experience_years' => ['required', 'integer', 'min:0'],
                'phone' => ['nullable', 'string', 'max:20'],
                'linkedin_url' => ['nullable', 'url', 'max:255'],
                'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            ]);
        } elseif ($request->role === 'company') {
            $rules = array_merge($rules, [
                'sector' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'company_size' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:20'],
                'tax_id' => ['required', 'string', 'max:255'],
                'website' => ['nullable', 'url', 'max:255'],
                'bio' => ['nullable', 'string', 'max:2000'],
                'logo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);
        }

        $request->validate($rules, [
            'name.required' => 'Le nom et prénom sont obligatoires.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'cv.required' => 'Le dépôt du CV est obligatoire.',
            'logo.required' => 'Le logo de l\'entreprise est obligatoire.',
            'birth_date.required' => 'La date de naissance est obligatoire.',
            'city.required' => 'La ville est obligatoire.',
            'domain.required' => 'Le domaine est obligatoire.',
            'education_level.required' => 'Le niveau d\'étude est obligatoire.',
            'experience_years.required' => 'Le nombre d\'années d\'expérience est obligatoire.',
            'sector.required' => 'Le secteur d\'activité est obligatoire.',
            'address.required' => 'L\'adresse est obligatoire.',
            'tax_id.required' => 'Le matricule fiscal est obligatoire.',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];

        if ($request->role === 'candidate') {
            $cvPath = $request->file('cv')->store('cvs', 'public');
            
            $userData = array_merge($userData, [
                'birth_date' => $request->birth_date,
                'city' => $request->city,
                'domain' => $request->domain,
                'education_level' => $request->education_level,
                'experience_years' => $request->experience_years,
                'phone' => $request->phone,
                'linkedin_url' => $request->linkedin_url,
                'cv_path' => $cvPath,
                'is_validated' => true, // Candidates are auto-validated
            ]);
        } elseif ($request->role === 'company') {
            $logoPath = $request->file('logo')->store('logos', 'public');
            
            $userData = array_merge($userData, [
                'sector' => $request->sector,
                'address' => $request->address,
                'city' => $request->city,
                'company_size' => $request->company_size,
                'phone' => $request->phone,
                'tax_id' => $request->tax_id,
                'website' => $request->website,
                'bio' => $request->bio,
                'logo_path' => $logoPath,
                'is_validated' => false, // Companies need admin validation
            ]);
        }

        $user = User::create($userData);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
