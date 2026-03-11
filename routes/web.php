<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, ProductController, InputController, OutputController, EmployeeController, CategoryController};

Route::middleware(['guest', 'prevent-back'])->group(function () {
    Route::get('/', fn() => view('auth.gate'))->name('gate');
    Route::get('/login/estoque', fn() => view('auth.login'))->name('login');
    Route::get('/login/pdv', fn() => view('auth.pdv-login'))->name('pdv.login');
    Route::post('/login/estoque', [AuthController::class, 'login']);
    Route::post('/login/pdv', [AuthController::class, 'login']);
    Route::get('/register', fn() => view('auth.register'))->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth', 'prevent-back'])->group(function () {
    Route::get('/dashboard', [ProductController::class, 'index'])->name('dashboard');

    // Módulo de Produtos
    Route::get('/produtos', [ProductController::class, 'list'])->name('produtos');
    Route::get('/produtos/novo', [ProductController::class, 'create'])->name('produtos.create');
    Route::post('/produtos', [ProductController::class, 'store'])->name('products.store');

    // Rota de exclusão em massa inserida antes do parâmetro dinâmico
    Route::delete('/produtos/bulk-destroy', [ProductController::class, 'bulkDestroy'])->name('products.bulk-destroy');

    Route::get('/produtos/{product}', [ProductController::class, 'show'])->name('produtos.show');
    Route::get('/produtos/{product}/editar', [ProductController::class, 'edit'])->name('produtos.edit');
    Route::put('/produtos/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/produtos/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/api/search', [ProductController::class, 'search'])->name('api.search');

    // Módulo de Categorias
    Route::get('/categorias', [CategoryController::class, 'index'])->name('categorias');
    Route::get('/categorias/nova', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categorias/{category}/editar', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categorias/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categorias/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Logística e Saídas
    Route::get('/entradas', [InputController::class, 'index'])->name('entradas');
    Route::get('/entradas/novo', [InputController::class, 'create'])->name('entradas.create');
    Route::post('/entradas', [InputController::class, 'store'])->name('entradas.store');
    Route::get('/saidas', [OutputController::class, 'index'])->name('saidas');
    Route::get('/saidas/registrar', [OutputController::class, 'create'])->name('saidas.create');
    Route::post('/saidas', [OutputController::class, 'store'])->name('saidas.store');
    Route::get('/saidas/comissoes', [OutputController::class, 'commissions'])->name('saidas.commissoes');
    Route::get('/saidas/resumo-diario', [OutputController::class, 'dailySummary'])->name('saidas.resumo');
    Route::get('/saidas/{output}/recibo', [OutputController::class, 'receipt'])->name('saidas.recibo');
    Route::get('/saidas/relatorio-diario', [OutputController::class, 'dailyReport'])->name('saidas.relatorio');
    Route::get('/saidas/auditoria-descontos', [OutputController::class, 'discountAudit'])->name('saidas.descontos');

    // Funcionários
    Route::get('/funcionarios', [EmployeeController::class, 'index'])->name('funcionarios');
    Route::get('/funcionarios/novo', [EmployeeController::class, 'create'])->name('funcionarios.create');
    Route::post('/funcionarios', [EmployeeController::class, 'store'])->name('funcionarios.store');
    Route::get('/funcionarios/{employee}/editar', [EmployeeController::class, 'edit'])->name('funcionarios.edit');
    Route::put('/funcionarios/{employee}', [EmployeeController::class, 'update'])->name('funcionarios.update');
    Route::patch('/funcionarios/{employee}/status', [EmployeeController::class, 'toggleStatus'])->name('funcionarios.toggle');
    Route::delete('/funcionarios/{employee}', [EmployeeController::class, 'destroy'])->name('funcionarios.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/pdv/logout', [AuthController::class, 'pdvLogout'])->name('pdv.logout');
});