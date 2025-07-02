<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Ebook;
use App\Models\EbookCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons
     */
    public function index(Request $request): View
    {
        $query = Coupon::with(['usages']);

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('valid_until', '<', now());
            }
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $coupons = $query->latest()->paginate(15);

        // Get statistics
        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::active()->count(),
            'inactive' => Coupon::where('is_active', false)->count(),
            'expired' => Coupon::where('valid_until', '<', now())->count(),
            'percentage' => Coupon::byType('percentage')->count(),
            'fixed' => Coupon::byType('fixed')->count(),
            'total_usage' => Coupon::sum('used_count'),
        ];

        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    /**
     * Show the form for creating a new coupon
     */
    public function create(): View
    {
        $ebooks = Ebook::all();
        $categories = EbookCategory::all();
        
        return view('admin.coupons.create', compact('ebooks', 'categories'));
    }

    /**
     * Store a newly created coupon
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'applicable_ebooks' => 'nullable|array',
            'applicable_ebooks.*' => 'integer|exists:ebooks,id',
            'applicable_categories' => 'nullable|array',
            'applicable_categories.*' => 'integer|exists:ebook_categories,id',
        ]);

        $coupon = Coupon::create([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'minimum_amount' => $request->minimum_amount,
            'usage_limit' => $request->usage_limit,
            'usage_limit_per_user' => $request->usage_limit_per_user,
            'is_active' => $request->boolean('is_active', true),
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'applicable_ebooks' => $request->applicable_ebooks,
            'applicable_categories' => $request->applicable_categories,
            'metadata' => [
                'created_by' => auth()->id(),
                'created_at' => now()->toISOString(),
            ],
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon créé avec succès.');
    }

    /**
     * Display the specified coupon
     */
    public function show(Coupon $coupon): View
    {
        $coupon->load(['usages.user', 'usages.order']);
        
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified coupon
     */
    public function edit(Coupon $coupon): View
    {
        $ebooks = Ebook::all();
        $categories = EbookCategory::all();
        
        return view('admin.coupons.edit', compact('coupon', 'ebooks', 'categories'));
    }

    /**
     * Update the specified coupon
     */
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'applicable_ebooks' => 'nullable|array',
            'applicable_ebooks.*' => 'integer|exists:ebooks,id',
            'applicable_categories' => 'nullable|array',
            'applicable_categories.*' => 'integer|exists:ebook_categories,id',
        ]);

        $coupon->update([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'minimum_amount' => $request->minimum_amount,
            'usage_limit' => $request->usage_limit,
            'usage_limit_per_user' => $request->usage_limit_per_user,
            'is_active' => $request->boolean('is_active', true),
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'applicable_ebooks' => $request->applicable_ebooks,
            'applicable_categories' => $request->applicable_categories,
            'metadata' => array_merge($coupon->metadata ?? [], [
                'updated_by' => auth()->id(),
                'updated_at' => now()->toISOString(),
            ]),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon mis à jour avec succès.');
    }

    /**
     * Remove the specified coupon
     */
    public function destroy(Coupon $coupon)
    {
        // Check if coupon has been used
        if ($coupon->used_count > 0) {
            return back()->with('error', 'Impossible de supprimer un coupon qui a été utilisé.');
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon supprimé avec succès.');
    }

    /**
     * Toggle coupon status
     */
    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update([
            'is_active' => !$coupon->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Statut du coupon mis à jour avec succès',
            'coupon' => $coupon->fresh(),
        ]);
    }

    /**
     * Get coupon details for modal
     */
    public function getCouponDetails(Coupon $coupon)
    {
        $coupon->load(['usages.user', 'usages.order']);
        
        return response()->json([
            'success' => true,
            'coupon' => $coupon,
        ]);
    }

    /**
     * Generate a unique coupon code
     */
    public function generateCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Coupon::where('code', $code)->exists());

        return response()->json([
            'success' => true,
            'code' => $code,
        ]);
    }

    /**
     * Get coupons statistics
     */
    public function getStats()
    {
        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::active()->count(),
            'inactive' => Coupon::where('is_active', false)->count(),
            'expired' => Coupon::where('valid_until', '<', now())->count(),
            'percentage' => Coupon::byType('percentage')->count(),
            'fixed' => Coupon::byType('fixed')->count(),
            'total_usage' => Coupon::sum('used_count'),
            'total_discount' => \App\Models\CouponUsage::sum('discount_amount'),
        ];

        return response()->json($stats);
    }
} 