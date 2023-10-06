<div role="status" id="toaster" x-data="toasterHub(@js($toasts), @js($config))" style="position: fixed;top: 0;right: 0;z-index: 2000;margin-top: 60px; margin-right: 10px; min-width: 350px;" @class([])>
    <template x-for="toast in toasts" :key="toast.id">
        <div role="alert" x-show="toast.isVisible"
             x-init="$nextTick(() => toast.show($el))"
             @if($alignment->is('bottom'))
             x-transition:enter-start="translate-y-12 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             @elseif($alignment->is('top'))
             x-transition:enter-start="-translate-y-12 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             @else
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             @endif
             x-transition:leave-end="opacity-0 scale-90"
             @class(['alert alert-important alert-dismissible', 'text-center' => $position->is('center')]) :class="toast.select({ error: 'alert-danger', info: 'alert-info', success: 'alert-success', warning: 'alert-warning' })"
        >
        <div class="d-flex">
            <div>
                <span x-text="toast.message"></span>
            </div>
        </div>

            @if($closeable)
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            @endif
        </div>
    </template>
</div>