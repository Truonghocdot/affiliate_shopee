<?php

use Livewire\Volt\Component;
use App\Models\Setting;

new class extends Component
{
    public $password = '';
    public $shopee_cookie = '';
    public $shopee_user_agent = '';

    public $isUnlocked = false;
    public $statusMessage = '';

    public function mount()
    {
        $this->shopee_cookie = Setting::get('shopee_cookie', '');
        $this->shopee_user_agent = Setting::get('shopee_user_agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    }

    public function unlock()
    {
        if ($this->password === config('app.admin_password', 'truong123')) {
            $this->isUnlocked = true;
            $this->statusMessage = '';
        } else {
            $this->statusMessage = 'Sai mật khẩu truy cập.';
        }
    }

    public function save()
    {
        if (!$this->isUnlocked) return;

        Setting::set('shopee_cookie', $this->shopee_cookie);
        Setting::set('shopee_user_agent', $this->shopee_user_agent);

        $this->statusMessage = 'Đã lưu cấu hình thành công!';
    }
};
?>

<div class="max-w-2xl mx-auto py-12 px-4">
    <div class="bg-white dark:bg-gray-900 shadow-2xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-800">
        <div class="bg-black p-8 text-white flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-black tracking-tight uppercase">Admin Settings</h2>
                <p class="text-gray-400 text-xs font-bold mt-1">QUẢN LÝ CẤU HÌNH HỆ THỐNG</p>
            </div>
            <div class="bg-[#ee4d2d] p-3 rounded-2xl shadow-lg shadow-orange-500/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <div class="p-8 space-y-8">
            @if(!$isUnlocked)
            <div class="space-y-4 animate-fade-in">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest text-center">Xác thực quyền quản trị</p>
                <div class="relative">
                    <input type="password" wire:model="password" placeholder="Nhập mật khẩu admin..."
                        class="w-full px-6 py-4 rounded-2xl bg-gray-50 dark:bg-gray-800 border-none outline-none focus:ring-2 focus:ring-[#ee4d2d] transition-all text-center font-bold">
                </div>
                <button wire:click="unlock" class="w-full bg-[#ee4d2d] text-white py-4 rounded-2xl font-black shadow-xl shadow-orange-500/10 active:scale-95 transition-all">
                    UNLOCK SETTINGS
                </button>
            </div>
            @else
            <div class="space-y-6 animate-fade-in">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Global Raw Cookie</label>
                    <textarea wire:model="shopee_cookie" rows="10" placeholder="Dán Shopee Cookie dùng chung tại đây..."
                        class="w-full px-4 py-4 rounded-2xl bg-gray-50 dark:bg-gray-800 border-none outline-none focus:ring-2 focus:ring-[#ee4d2d] transition-all text-xs font-mono"></textarea>
                    <p class="mt-2 text-[10px] text-gray-400 italic">* Lưu ý: Cookie này sẽ được sử dụng cho TẤT CẢ khách hàng bên ngoài.</p>
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Global User Agent</label>
                    <input type="text" wire:model="shopee_user_agent"
                        class="w-full px-4 py-4 rounded-2xl bg-gray-50 dark:bg-gray-800 border-none outline-none focus:ring-2 focus:ring-[#ee4d2d] transition-all text-sm font-medium">
                </div>

                <button wire:click="save" class="w-full bg-black text-white py-5 rounded-2xl font-black shadow-2xl active:scale-95 transition-all flex justify-center items-center gap-2">
                    <svg class="w-5 h-5 text-[#ee4d2d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    SAVE GLOBAL CONFIGURATION
                </button>
            </div>
            @endif

            @if($statusMessage)
            <div class="p-4 rounded-2xl text-center text-sm font-bold {{ str_contains($statusMessage, 'thành công') ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                {{ $statusMessage }}
            </div>
            @endif
        </div>
    </div>

    <div class="mt-8 text-center">
        <a href="/" class="text-xs font-bold text-gray-400 hover:text-[#ee4d2d] transition-colors uppercase tracking-widest">
            ← Back to Converter
        </a>
    </div>
</div>