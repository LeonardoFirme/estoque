<?php
// app/Http/Controllers/EmployeeController.php
namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::latest()->paginate(10);
        return view('employees.index', compact('employees'));
    }

    public function create(): View
    {
        return view('employees.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:employees,email'],
            'cpf' => ['required', 'string', 'unique:employees,cpf'],
            'role' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Employee::create($validated);

        return redirect()->route('funcionarios')->with('success', 'Funcionário cadastrado com sucesso.');
    }

    public function edit(Employee $employee): View
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('employees', 'email')->ignore($employee->id)
            ],
            'cpf' => [
                'required',
                'string',
                Rule::unique('employees', 'cpf')->ignore($employee->id)
            ],
            'role' => ['required', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $employee->update($validated);

        return redirect()->route('funcionarios')->with('success', 'Dados do funcionário atualizados.');
    }

    public function toggleStatus(Employee $employee): RedirectResponse
    {
        $employee->update(['is_active' => !$employee->is_active]);

        $status = $employee->is_active ? 'ativado' : 'inativado';
        return back()->with('success', "Funcionário {$status} com sucesso.");
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();
        return redirect()->route('funcionarios')->with('success', 'Funcionário removido do sistema.');
    }
}