<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = Seller::withCount('products')->latest()->paginate(15);
        return view('admin.sellers.index', compact('sellers'));
    }

    public function show(Seller $seller)
    {
        $seller->load(['products', 'orderItems.order']);
        return view('admin.sellers.show', compact('seller'));
    }

    public function toggleStatus(Seller $seller)
    {
        $seller->update(['is_active' => !$seller->is_active]);
        $status = $seller->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Seller has been {$status}.");
    }

    public function updateCommission(Request $request, Seller $seller)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $seller->update(['commission_rate' => $request->commission_rate]);

        return back()->with('success', 'Commission rate updated.');
    }

    public function destroy(Seller $seller)
    {
        // Check if seller has products before allowing deletion
        if ($seller->products()->count() > 0) {
            return back()->with('error', 'Cannot delete seller with existing products. Please remove all products first.');
        }

        $seller->delete();

        return back()->with('success', 'Seller deleted successfully.');
    }
}
