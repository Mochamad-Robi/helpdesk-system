<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HelpdeskController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Auth::routes(['register' => false]); // Disable public registration

// Custom Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Role-based Redirect After Login
|--------------------------------------------------------------------------
*/

Route::get('/home', function () {
    $user = auth()->user();
    
    if ($user->isDealer()) {
        return redirect()->route('dealer.dashboard');
    } elseif ($user->isAdminIT() || $user->isSuperAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isHelpdesk()) {
        return redirect()->route('helpdesk.dashboard');
    }
    
    return redirect('/');
})->middleware('auth')->name('home');

/*
|--------------------------------------------------------------------------
| DEALER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('dealer')->name('dealer.')->middleware(['auth', 'dealer'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DealerController::class, 'dashboard'])->name('dashboard');
    
    // Tickets
    Route::get('/tickets', [TicketController::class, 'dealerIndex'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    
    // Get Sub-Categories by Category (AJAX)
    Route::get('/api/subcategories/{category}', [TicketController::class, 'getSubCategories'])
         ->name('api.subcategories');
    
    // Ticket Actions
    Route::post('/tickets/{ticket}/comment', [TicketController::class, 'addComment'])->name('tickets.comment');
    Route::post('/tickets/{ticket}/reopen', [TicketController::class, 'reopen'])->name('tickets.reopen');
    
    // Profile
    Route::get('/profile', [DealerController::class, 'profile'])->name('profile');
    Route::put('/profile', [DealerController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| ADMIN IT ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin_it'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Tickets Management
    Route::get('/tickets', [AdminController::class, 'tickets'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [AdminController::class, 'ticketDetail'])->name('tickets.show');
    Route::post('/tickets/{ticket}/assign', [AdminController::class, 'assignTicket'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/reassign', [AdminController::class, 'reassignTicket'])->name('tickets.reassign');
    
    // Categories Management
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/{category}/toggle', [CategoryController::class, 'toggleActive'])->name('categories.toggle');
    
    // Sub-Categories Management
    Route::get('/categories/{category}/subcategories', [CategoryController::class, 'subCategories'])->name('categories.subcategories');
    Route::post('/categories/{category}/subcategories', [CategoryController::class, 'storeSubCategory'])->name('subcategories.store');
    Route::put('/subcategories/{subCategory}', [CategoryController::class, 'updateSubCategory'])->name('subcategories.update');
    Route::delete('/subcategories/{subCategory}', [CategoryController::class, 'destroySubCategory'])->name('subcategories.destroy');
    
    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');
    
    // Dealer Branches Management
    Route::get('/branches', [AdminController::class, 'branches'])->name('branches.index');
    Route::post('/branches', [AdminController::class, 'storeBranch'])->name('branches.store');
    Route::put('/branches/{branch}', [AdminController::class, 'updateBranch'])->name('branches.update');
    Route::delete('/branches/{branch}', [AdminController::class, 'deleteBranch'])->name('branches.destroy');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports.index');
    Route::get('/reports/export', [AdminController::class, 'exportReport'])->name('reports.export');
});

/*
|--------------------------------------------------------------------------
| HELPDESK ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('helpdesk')->name('helpdesk.')->middleware(['auth', 'helpdesk'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [HelpdeskController::class, 'dashboard'])->name('dashboard');
    
    // My Tickets (Assigned to me)
    Route::get('/tickets', [HelpdeskController::class, 'myTickets'])->name('tickets.index');
    Route::get('/tickets/all', [HelpdeskController::class, 'allTickets'])->name('tickets.all');
    Route::get('/tickets/{ticket}', [HelpdeskController::class, 'ticketDetail'])->name('tickets.show');
    
    // Ticket Actions
    Route::post('/tickets/{ticket}/start', [HelpdeskController::class, 'startWorking'])->name('tickets.start');
    Route::post('/tickets/{ticket}/comment', [HelpdeskController::class, 'addComment'])->name('tickets.comment');
    Route::post('/tickets/{ticket}/resolve', [HelpdeskController::class, 'resolve'])->name('tickets.resolve');
    Route::post('/tickets/{ticket}/pending', [HelpdeskController::class, 'setPending'])->name('tickets.pending');
    Route::post('/tickets/{ticket}/escalate', [HelpdeskController::class, 'escalate'])->name('tickets.escalate');
    
    // Attachments
    Route::post('/tickets/{ticket}/attachments', [HelpdeskController::class, 'uploadAttachment'])->name('tickets.attachments.upload');
    
    // Profile
    Route::get('/profile', [HelpdeskController::class, 'profile'])->name('profile');
    Route::put('/profile', [HelpdeskController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| COMMON ROUTES (IT Staff - Admin + Helpdesk)
|--------------------------------------------------------------------------
*/

Route::prefix('common')->name('common.')->middleware(['auth', 'it_staff'])->group(function () {
    
    // Download Attachments
    Route::get('/attachments/{attachment}/download', function ($attachmentId) {
        $attachment = \App\Models\TicketAttachment::findOrFail($attachmentId);
        return response()->download(storage_path('app/' . $attachment->file_path));
    })->name('attachments.download');
    
});