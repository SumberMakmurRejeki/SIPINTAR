{{-- Toast Container - Alpine.js powered --}}
{{-- Reads flash messages: success, error, warning, info --}}
@php
    $toasts = [];
    if(session('success')) { $toasts[] = ['type' => 'success', 'message' => session('success')]; }
    if(session('error')) { $toasts[] = ['type' => 'error', 'message' => session('error')]; }
    if(session('warning')) { $toasts[] = ['type' => 'warning', 'message' => session('warning')]; }
    if(session('info')) { $toasts[] = ['type' => 'info', 'message' => session('info')]; }
@endphp

@if(count($toasts) > 0)
    <div
        x-data="{
            toasts: {{ Js::from($toasts) }},
            remove(index) {
                this.toasts.splice(index, 1);
            },
            autoDismiss(index) {
                setTimeout(() => this.remove(index), 5000);
            }
        }"
        class="fixed bottom-4 right-4 z-50 flex flex-col gap-2 max-w-sm"
        x-cloak
    >
        <template x-for="(toast, index) in toasts" :key="index">
            <div
                x-init="autoDismiss(index)"
                x-show="true"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                :class="{
                    'border-green-200 bg-white text-green-800': toast.type === 'success',
                    'border-red-200 bg-white text-red-800': toast.type === 'error',
                    'border-yellow-200 bg-white text-yellow-800': toast.type === 'warning',
                    'border-blue-200 bg-white text-blue-800': toast.type === 'info',
                }"
                class="rounded-lg border px-4 py-3 text-sm shadow-sm flex items-start gap-3"
            >
                {{-- Icon --}}
                <div class="shrink-0 mt-0.5">
                    <svg x-show="toast.type === 'success'" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg x-show="toast.type === 'error'" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <svg x-show="toast.type === 'warning'" class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <svg x-show="toast.type === 'info'" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </div>
                <span class="flex-1" x-text="toast.message"></span>
                <button @click="remove(index)" class="shrink-0 text-gray-400 hover:text-gray-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
    </div>
@endif
