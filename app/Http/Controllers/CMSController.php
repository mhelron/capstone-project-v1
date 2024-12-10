<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CMSController extends Controller
{
    protected $database, $contents;
    public function __construct(Database $database) {
        $this->database = $database;
        $this->contents = 'contents';
    }
    public function editHome()
    {
        $content = $this->database->getReference('contents')->getValue();

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.content.home.index', compact('content', 'isExpanded'));
    }

    public function updateHome(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:30720',
            'logo_footer' => 'nullable|image|mimes:png,jpg,jpeg|max:30720',
            'title_nav' => 'nullable|string',  // Removed max:255 since we're using rich text
            'title_home' => 'nullable|string',
            'title_footer' => 'nullable|string',
            'headline' => 'nullable|string',
            'subtext' => 'nullable|string',
            'number2' => 'nullable|string|max:20',
        ]);

        // Strip tags but allow specific HTML elements
        $updates = [
            'title_nav' => strip_tags($request->input('title_nav', ''), 
                '<p><br><strong><em><ul><ol><li><i><span><div><h1><h2><h3><h4><h5><h6>'),
            'title_home' => strip_tags($request->input('title_home', ''),
                '<p><br><strong><em><ul><ol><li><i><span><div><h1><h2><h3><h4><h5><h6>'),
            'title_footer' => strip_tags($request->input('title_footer', ''),
                '<p><br><strong><em><ul><ol><li><i><span><div><h1><h2><h3><h4><h5><h6>'),
            'headline' => strip_tags($request->input('headline', ''),
                '<p><br><strong><em><ul><ol><li><i><span><div><h1><h2><h3><h4><h5><h6>'),
            'subtext' => strip_tags($request->input('subtext', ''),
                '<p><br><strong><em><ul><ol><li><i><span><div><h1><h2><h3><h4><h5><h6>'),
            'address' => $request->input('address', ''),
            'number1' => $request->input('number1', ''),
            'number2' => $request->input('number2', ''),    
        ];

        // Handle header logo upload
        if ($request->hasFile('logo')) {
            $currentContent = $this->database->getReference('contents')->getValue();
            
            if (isset($currentContent['logo_path']) && Storage::disk('public')->exists($currentContent['logo_path'])) {
                Storage::disk('public')->delete($currentContent['logo_path']);
            }

            $logoFile = $request->file('logo');
            $filename = time() . '_' . $logoFile->getClientOriginalName();
            $logoPath = $logoFile->storeAs('logos', $filename, 'public');
            $updates['logo_path'] = $logoPath;
        }

        // Handle footer logo upload
        if ($request->hasFile('logo_footer')) {
            $currentContent = $this->database->getReference('contents')->getValue();
            
            if (isset($currentContent['logo_footer_path']) && Storage::disk('public')->exists($currentContent['logo_footer_path'])) {
                Storage::disk('public')->delete($currentContent['logo_footer_path']);
            }

            $logoFooterFile = $request->file('logo_footer');
            $filename = time() . '_' . $logoFooterFile->getClientOriginalName();
            $logoFooterPath = $logoFooterFile->storeAs('logos', $filename, 'public');
            $updates['logo_footer_path'] = $logoFooterPath;
        }

        // Update Firebase
        $this->database->getReference('contents')->update($updates);

        return redirect()->route('admin.home.edit')->with('status', 'Content updated successfully!');
    }

    public function editCarousel()
    {
        $content = $this->database->getReference('contents')->getValue();
        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.content.carousel.index', compact('content', 'isExpanded'));
    }

    public function updateCarousel(Request $request)
    {
        $request->validate([
            'carousel_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:30720'
        ], [
            'carousel_images.*.required' => 'Please select an image.',
            'carousel_images.*.image' => 'The file must be an image.',
            'carousel_images.*.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'carousel_images.*.max' => 'The image must not be greater than 2MB.'
        ]);

        $updates = [];
        $carouselImages = [];
        $currentContent = $this->database->getReference('contents')->getValue();
        $existingImages = isset($currentContent['carousel_images']) ? $currentContent['carousel_images'] : [];

        // Get existing images that are being kept
        if ($request->has('existing_images')) {
            $carouselImages = $request->existing_images;
            
            // Delete images that were removed (not in existing_images anymore)
            foreach ($existingImages as $oldImage) {
                if (!in_array($oldImage, $carouselImages)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        }

        // Handle image uploads and replacements
        if ($request->hasFile('carousel_images')) {
            foreach ($request->file('carousel_images') as $index => $image) {
                if ($image) {
                    // If there's an existing image at this index, delete it
                    if (isset($carouselImages[$index]) && Storage::disk('public')->exists($carouselImages[$index])) {
                        Storage::disk('public')->delete($carouselImages[$index]);
                    }

                    // Store new image
                    $filename = time() . '_' . $image->getClientOriginalName();
                    $imagePath = $image->storeAs('carousel', $filename, 'public');
                    $carouselImages[$index] = $imagePath;
                }
            }
        }

        // Remove any null values and reindex array
        $carouselImages = array_values(array_filter($carouselImages));
        
        $updates['carousel_images'] = $carouselImages;

        // Update Firebase
        $this->database->getReference('contents')->update($updates);

        return redirect()->route('admin.carousel.edit')
            ->with('status', 'Content updated successfully!');
    }
}
