<?php

namespace App\Livewire\Actions;

use App\Livewire\Forms\HomePageForm;
use App\Models\HomePage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageHomePage extends Component
{
    use WithFileUploads;

    public HomePageForm $form;
    public $activeTab = 'hero';
    public $locale = 'ar';
    public $homePageId;

    // Temporary uploads
    public $temp_cyber_sale_image;
    public $temp_featured_banner_image;
    public $temp_spotlight_banner_image;
    public $temp_hero_slide_image;
    public $temp_gifts_image = [];
    public $temp_discover_image = [];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $homePage = HomePage::where('locale', $this->locale)->where('is_active', true)->first();

        if ($homePage) {
            $this->homePageId = $homePage->id;
            $this->form->setHomePage($homePage);
        } else {
            $this->form->locale = $this->locale;
        }
    }

    public function updatedLocale()
    {
        $homePage = HomePage::where('locale', $this->locale)->where('is_active', true)->first();

        if ($homePage) {
            $this->homePageId = $homePage->id;
            $this->form->setHomePage($homePage);
        } else {
            $this->form->reset();
            $this->form->locale = $this->locale;
        }
    }

    public function addHeroSlide()
    {
        if ($this->temp_hero_slide_image) {
            $path = $this->form->handleImageUpload($this->temp_hero_slide_image, 'home-page/hero');

            $this->form->hero_slides[] = [
                'image' => $path,
                'link' => '#',
                'alt' => 'Hero Banner'
            ];

            $this->temp_hero_slide_image = null;
            $this->dispatch('alert', ['type' => 'success', 'message' => 'تمت إضافة الصورة بنجاح']);
        }
    }

    public function removeHeroSlide($index)
    {
        unset($this->form->hero_slides[$index]);
        $this->form->hero_slides = array_values($this->form->hero_slides);
        $this->dispatch('alert', ['type' => 'success', 'message' => 'تم حذف الصورة بنجاح']);
    }

    public function updateHeroSlideLink($index, $link)
    {
        if (isset($this->form->hero_slides[$index])) {
            $this->form->hero_slides[$index]['link'] = $link;
        }
    }

    public function uploadCyberSaleImage()
    {
        if ($this->temp_cyber_sale_image) {
            $this->form->cyber_sale_image = $this->form->handleImageUpload($this->temp_cyber_sale_image, 'home-page/cyber-sale');
            $this->temp_cyber_sale_image = null;
            $this->dispatch('alert', ['type' => 'success', 'message' => 'تم رفع الصورة بنجاح']);
        }
    }

    public function addGiftItem()
    {
        $this->form->gifts_items[] = [
            'image' => '',
            'title' => ['ar' => '', 'en' => ''],
            'link' => '#'
        ];
    }

    public function removeGiftItem($index)
    {
        unset($this->form->gifts_items[$index]);
        $this->form->gifts_items = array_values($this->form->gifts_items);
        $this->dispatch('alert', ['type' => 'success', 'message' => 'تم حذف العنصر بنجاح']);
    }

    public function uploadGiftImage($index)
    {
        if (isset($this->temp_gifts_image[$index])) {
            $path = $this->form->handleImageUpload($this->temp_gifts_image[$index], 'home-page/gifts');
            $this->form->gifts_items[$index]['image'] = $path;
            unset($this->temp_gifts_image[$index]);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'تم رفع الصورة بنجاح']);
        }
    }

    public function uploadFeaturedBannerImage()
    {
        if ($this->temp_featured_banner_image) {
            $this->form->featured_banner_image = $this->form->handleImageUpload($this->temp_featured_banner_image, 'home-page/featured');
            $this->temp_featured_banner_image = null;
            $this->dispatch('alert', ['type' => 'success', 'message' => 'تم رفع الصورة بنجاح']);
        }
    }

    public function uploadSpotlightBannerImage()
    {
        if ($this->temp_spotlight_banner_image) {
            $this->form->spotlight_banner_image = $this->form->handleImageUpload($this->temp_spotlight_banner_image, 'home-page/spotlight');
            $this->temp_spotlight_banner_image = null;
            $this->dispatch('alert', ['type' => 'success', 'message' => 'تم رفع الصورة بنجاح']);
        }
    }

    public function addDiscoverItem()
    {
        $this->form->discover_items[] = [
            'image' => '',
            'title' => ['ar' => '', 'en' => ''],
            'link' => '#',
            'type' => 'small'
        ];
    }

    public function removeDiscoverItem($index)
    {
        unset($this->form->discover_items[$index]);
        $this->form->discover_items = array_values($this->form->discover_items);
        $this->dispatch('alert', ['type' => 'success', 'message' => 'تم حذف العنصر بنجاح']);
    }

    public function uploadDiscoverImage($index)
    {
        if (isset($this->temp_discover_image[$index])) {
            $path = $this->form->handleImageUpload($this->temp_discover_image[$index], 'home-page/discover');
            $this->form->discover_items[$index]['image'] = $path;
            unset($this->temp_discover_image[$index]);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'تم رفع الصورة بنجاح']);
        }
    }

    public function save()
    {
        try {
            if ($this->homePageId) {
                $homePage = HomePage::find($this->homePageId);
                $this->form->setHomePage($homePage);
                $this->form->update();
                $message = 'تم تحديث محتوى الصفحة الرئيسية بنجاح';
            } else {
                $homePage = $this->form->store();
                $this->homePageId = $homePage->id;
                $message = 'تم حفظ محتوى الصفحة الرئيسية بنجاح';
            }

            $this->dispatch('alert', ['type' => 'success', 'message' => $message]);
            return redirect()->route('filament.admin.pages.manage-home-page');
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'حدث خطأ: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.actions.manage-home-page');
    }
}
