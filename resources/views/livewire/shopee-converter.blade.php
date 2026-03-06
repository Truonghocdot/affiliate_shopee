<?php

use Livewire\Volt\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

new class extends Component
{
    public $originalLink = '';
    public $subId1 = '';
    public $subId2 = '';
    public $subId3 = '';
    public $subId4 = '';
    public $subId5 = '';

    public $shortLink = '';
    public $errorMessage = '';
    public $showExtra = false;

    public function toggleExtra()
    {
        $this->showExtra = !$this->showExtra;
    }

    public function convert()
    {
        $this->validate([
            'originalLink' => 'required|url',
        ]);

        $this->errorMessage = '';
        $this->shortLink = '';

        $user = Auth::user();

        if ($user->conversion_count >= 3) {
            $this->errorMessage = 'Bạn đã hết lượt convert (Tối đa 3 lần).';
            return;
        }

        // Lấy Cookie và User-Agent từ Admin Settings
        $rawCookie = Setting::get('shopee_cookie');
        $userAgent = Setting::get('shopee_user_agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');

        if (empty($rawCookie)) {
            $this->errorMessage = 'Hệ thống chưa được cấu hình Cookie. Vui lòng liên hệ Admin.';
            return;
        }

        // Tách csrftoken, đôi khi Shopee yêu cầu header name khác (csrf-token)
        $csrfToken = '';
        if (preg_match('/csrftoken\s*=\s*([^;]+)/i', $rawCookie, $matches)) {
            $csrfToken = trim($matches[1]);
        } elseif (preg_match('/SPC_F\s*=\s*([^;]+)/i', $rawCookie, $matches)) {
            $csrfToken = trim($matches[1]);
            $rawCookie .= '; csrftoken=' . $csrfToken;
        } else {
            $csrfToken = \Illuminate\Support\Str::random(32);
            $rawCookie .= '; csrftoken=' . $csrfToken;
        }

        if (empty($csrfToken)) {
            $this->errorMessage = 'Có lỗi xảy ra vui lòng thử lại sau.';
            return;
        }

        $client = new Client();

        try {
            $response = $client->post('https://affiliate.shopee.vn/api/v3/gql?q=batchCustomLink', [
                'headers' => [
                    'Cookie' => $rawCookie,
                    'csrf-token' => $csrfToken,
                    'User-Agent' => $userAgent,
                    'Accept' => 'application/json, text/plain, */*',
                    'Accept-Language' => 'en-US,en;q=0.9,vi;q=0.8',
                    'Affiliate-Program-Type' => '1',
                    'Content-Type' => 'application/json; charset=UTF-8',
                    'Origin' => 'https://affiliate.shopee.vn',
                    'Referer' => 'https://affiliate.shopee.vn/offer/custom_link'
                ],
                'json' => [
                    'operationName' => 'batchGetCustomLink',
                    'variables' => [
                        'linkParams' => [
                            [
                                'originalLink' => $this->originalLink,
                                'advancedLinkParams' => [
                                    'subId1' => $this->subId1 ?? '',
                                    'subId2' => $this->subId2 ?? '',
                                    'subId3' => $this->subId3 ?? '',
                                    'subId4' => $this->subId4 ?? '',
                                    'subId5' => $this->subId5 ?? '',
                                ],
                            ]
                        ],
                        'sourceCaller' => 'CUSTOM_LINK_CALLER',
                    ],
                    'query' => "query batchGetCustomLink(\$linkParams: [CustomLinkParam!], \$sourceCaller: SourceCaller){ batchCustomLink(linkParams: \$linkParams, sourceCaller: \$sourceCaller){ shortLink longLink failCode } }",
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['data']['batchCustomLink'][0]['shortLink'])) {
                $this->shortLink = $result['data']['batchCustomLink'][0]['shortLink'];

                // Cập nhật lượt sử dụng
                $user->increment('conversion_count');
            } else {
                $failCode = $result['data']['batchCustomLink'][0]['failCode'] ?? 'Unknown';
                $this->errorMessage = "Lỗi từ Shopee: $failCode. Có thể Cookie hệ thống đã hết hạn.";
                \Illuminate\Support\Facades\Log::error("Shopee API Failed: " . json_encode($result));
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            \Illuminate\Support\Facades\Log::error('Shopee API Error (ClientException)', ['body' => $responseBody]);
            $this->errorMessage = 'Shopee API báo lỗi chặn (403/4xx): ' . $responseBody;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Shopee API Connection Error', ['message' => $e->getMessage()]);
            $this->errorMessage = 'Lỗi kết nối: ' . $e->getMessage();
        }
    }
};
?>

<div class="w-full max-w-2xl mx-auto bg-white dark:bg-gray-900 shopee-shadow rounded-[2rem] overflow-hidden border border-gray-100 dark:border-gray-800">
    <div class="bg-gradient-to-br from-[#ee4d2d] to-[#ff7337] p-8 text-white">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-3xl font-black flex items-center gap-3">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20,4h-4V2c0-0.55-0.45-1-1-1h-2c-0.55,0-1,0.45-1,1v2h-4V2c0-0.55-0.45-1-1-1h-2C4.45,1,4,1.45,4,2v2H0v18h24V4H20z M6,3h2v1H6V3z M14,3h2v1h-2V3z M22,20H2V6h20V20z M4,8h11v10H4V8z" />
                    </svg>
                    Converter
                </h2>
                <p class="opacity-80 text-xs font-bold mt-2 uppercase tracking-widest">Hiệu suất tối đa - An toàn tuyệt đối</p>
            </div>
            <div class="bg-white/20 backdrop-blur-md px-4 py-2 rounded-2xl text-center">
                <p class="text-[10px] font-bold uppercase">Lượt dùng</p>
                <p class="text-xl font-black mt-1">{{ 3 - Auth::user()->conversion_count }}<span class="text-xs opacity-50 ml-1">/ 3</span></p>
            </div>
        </div>
    </div>

    <div class="p-10 space-y-8">
        <div>
            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Copy & Paste Shopee Link</label>
            <div class="relative group">
                <input type="text" wire:model="originalLink" placeholder="https://shopee.vn/product-link-..."
                    class="w-full px-6 py-5 rounded-2xl bg-gray-50 dark:bg-gray-800 border-2 border-transparent focus:border-[#ee4d2d] focus:bg-white dark:focus:bg-gray-900 outline-none transition-all shadow-sm group-hover:shadow-md font-medium">
                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Tracking Sub-IDs</label>
                <button wire:click="toggleExtra" class="text-[#ee4d2d] text-[10px] font-black uppercase hover:underline tracking-tighter">
                    {{ $showExtra ? '- HIDE ADVANCED' : '+ SHOW ADVANCED' }}
                </button>
            </div>

            @if($showExtra)
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 animate-fade-in">
                @for($i=1; $i<=5; $i++)
                    <div class="relative group">
                    <input type="text" wire:model="subId{{ $i }}" placeholder="ID{{ $i }}"
                        class="w-full px-3 py-3 text-xs rounded-xl bg-gray-50 dark:bg-gray-800 border-none outline-none focus:ring-2 focus:ring-[#ee4d2d] transition-all font-bold text-center">
            </div>
            @endfor
        </div>
        @endif
    </div>

    <button wire:click="convert" wire:loading.attr="disabled"
        class="w-full bg-black text-white font-black py-6 rounded-[1.5rem] shadow-2xl transform active:scale-95 transition-all flex justify-center items-center gap-3 group relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-[#ee4d2d] to-[#ff7337] translate-y-[100%] group-hover:translate-y-0 transition-transform duration-500"></div>
        <span class="relative z-10" wire:loading.remove>GENERATE AFFILIATE LINK</span>
        <span wire:loading class="relative z-10 flex items-center gap-2">
            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            PROCESSING...
        </span>
    </button>

    @if($shortLink)
    <div class="mt-8 p-6 bg-gradient-to-br from-[#ee4d2d]/10 to-orange-500/10 border-2 border-dashed border-[#ee4d2d]/30 rounded-[2rem] animate-fade-in">
        <label class="block text-[10px] font-black text-[#ee4d2d] uppercase tracking-widest mb-3">CONVERTED SUCCESSFUL:</label>
        <div class="flex gap-3">
            <input type="text" readonly value="{{ $shortLink }}" id="resultLink"
                class="flex-1 bg-white dark:bg-gray-900 border-none outline-none font-black text-xl text-[#ee4d2d] px-4 py-2 rounded-xl shadow-inner italic">
            <button onclick="copyLink()" class="bg-[#ee4d2d] text-white p-4 rounded-xl shadow-lg hover:bg-black transition-all active:scale-90">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                </svg>
            </button>
        </div>
    </div>
    @endif

    @if($errorMessage)
    <div class="mt-4 p-5 bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900 rounded-2xl text-red-500 dark:text-red-400 text-xs font-bold flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        {{ $errorMessage }}
    </div>
    @endif
</div>

<script>
    function copyLink() {
        var copyText = document.getElementById("resultLink");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        alert("Copied to clipboard: " + copyText.value);
    }
</script>
</div>