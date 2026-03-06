<?php

use Livewire\Component;

new class extends Component
{
    public $count = 0;

    public function increment()
    {
        $this->count++;
    }
};
?>

<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md text-center">
    <h2 class="text-2xl font-bold mb-4 dark:text-white">Counter: {{ $count }}</h2>
    <button
        wire:click="increment"
        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
        Increment
    </button>
</div>