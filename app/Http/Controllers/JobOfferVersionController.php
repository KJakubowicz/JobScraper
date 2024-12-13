<?php

namespace App\Http\Controllers;

use App\Models\JobOfferVersion;
use Illuminate\Http\RedirectResponse;

class JobOfferVersionController extends Controller
{
    /**
     * Ustawia aktywną wersję oferty pracy.
     *
     * @param int $versionId
     * @return RedirectResponse
     */
    public function setActiveVersion(int $versionId): RedirectResponse
    {
        $version = JobOfferVersion::findOrFail($versionId);
        $version->jobOffer->versions()->update(['is_active' => false]);
        $version->update(['is_active' => true]);

        return redirect()->back()->with('status', 'Active version updated successfully.');
    }

    /**
     * Usuwa wersję oferty pracy.
     *
     * @param int $versionId
     * @return RedirectResponse
     */
    public function deleteVersion(int $versionId): RedirectResponse
    {
        $version = JobOfferVersion::findOrFail($versionId);
        $version->delete();

        return redirect()->back()->with('status', 'Version deleted successfully.');
    }
}
