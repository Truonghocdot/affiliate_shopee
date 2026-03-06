<?php

use Livewire\Volt\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public $originalLink = '';
    public $subId1 = '';
    public $subId2 = '';
    public $subId3 = '';
    public $subId4 = '';
    public $subId5 = '';
    public $rawCookie = '';
    public $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

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
            'rawCookie' => 'required',
        ]);

        $this->errorMessage = '';
        $this->shortLink = '';

        $user = Auth::user();

        if ($user->conversion_count >= 3) {
            $this->errorMessage = 'Bạn đã hết lượt convert (Tối đa 3 lần).';
            return;
        }

        // Tách csrftoken từ Cookie
        $csrfToken = '';
        if (preg_match('/csrftoken=([^;]+)/', $this->rawCookie, $matches)) {
            $csrfToken = $matches[1];
        }

        if (empty($csrfToken)) {
            $this->errorMessage = 'Không tìm thấy csrftoken trong Cookie. Hãy đảm bảo bạn dán đúng Raw Cookie.';
            return;
        }

        $client = new Client();

        try {
            $response = $client->post('https://affiliate.shopee.vn/api/v3/gql?q=batchCustomLink', [
                'headers' => [
                    'Cookie' => $this->rawCookie,
                    'X-Csrftoken' => $csrfToken,
                    'User-Agent' => $this->userAgent,
                    'Referer' => 'https://affiliate.shopee.vn/offer/custom_link',
                    'Content-Type' => 'application/json',
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
                $this->errorMessage = "Lỗi từ Shopee: $failCode. Hãy kiểm tra lại Cookie.";
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Lỗi kết nối: ' . $e->getMessage();
        }
    }
};
?>

<div class="w-full max-w-2xl mx-auto bg-white dark:bg-gray-900 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800">
    <div class="bg-[#ee4d2d] p-6 text-white">
        <h2 class="text-2xl font-bold flex items-center gap-2">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20,4h-4V2c0-0.55-0.45-1-1-1h-2c-0.55,0-1,0.45-1,1v2h-4V2c0-0.55-0.45-1-1-1h-2C4.45,1,4,1.45,4,2v2H0v18h24V4H20z M6,3h2v1H6V3z M14,3h2v1h-2V3z M22,20H2V6h20V20z M4,8h11v10H4V8z" />
            </svg>
            Shopee Affiliate Converter
        </h2>
        <p class="opacity-90 text-sm mt-1">Lượt convert còn lại: {{ 3 - Auth::user()->conversion_count }} / 3</p>
    </div>

    <div class="p-8 space-y-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Shopee Product Link</label>
            <input type="text" wire:model="originalLink" placeholder="https://shopee.vn/product-link-..."
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:ring-2 focus:ring-[#ee4d2d] focus:border-transparent outline-none transition-all">
        </div>

        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Raw Cookie</label>
                <button wire:click="toggleExtra" class="text-[#ee4d2d] text-xs font-bold hover:underline">
                    {{ $showExtra ? 'Thu gọn SubID' : 'Mở rộng SubID' }}
                </button>
            </div>
            <textarea wire:model="rawCookie" rows="3" placeholder="Dán raw cookie từ trình duyệt vào đây..."
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:ring-2 focus:ring-[#ee4d2d] focus:border-transparent outline-none transition-all text-xs font-mono"></textarea>
        </div>

        @if($showExtra)
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 animate-fade-in">
            @for($i=1; $i<=5; $i++)
                <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">SubID {{ $i }}</label>
                <input type="text" wire:model="subId{{ $i }}" placeholder="..."
                    class="w-full px-2 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-700 dark:bg-gray-800 outline-none focus:border-[#ee4d2d]">
        </div>
        @endfor
    </div>
    @endif

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">User Agent (Tùy chọn)</label>
        <input type="text" wire:model="userAgent"
            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 outline-none text-xs">
    </div>

    <button wire:click="convert" wire:loading.attr="disabled"
        class="w-full bg-[#ee4d2d] hover:bg-[#d73211] text-white font-bold py-4 rounded-xl shadow-lg transform active:scale-95 transition-all flex justify-center items-center gap-2">
        <span wire:loading.remove>CHUYỂN ĐỔI NGAY</span>
        <span wire:loading class="flex items-center gap-2">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Đang xử lý...
        </span>
    </button>

    @if($shortLink)
    <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
        <label class="block text-xs font-bold text-green-700 dark:text-green-400 mb-1">KẾT QUẢ:</label>
        <div class="flex gap-2">
            <input type="text" readonly value="{{ $shortLink }}" id="resultLink"
                class="flex-1 bg-white dark:bg-gray-800 border-none outline-none font-bold text-[#ee4d2d] px-2 py-1 rounded">
            <button onclick="copyLink()" class="bg-[#ee4d2d] text-white px-4 py-1 rounded-md text-sm font-bold active:bg-black">
                COPY
            </button>
        </div>
    </div>
    @endif

    @if($errorMessage)
    <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-600 dark:text-red-400 text-sm font-medium">
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
        alert("Đã copy: " + copyText.value);
    }
</script>
</div>