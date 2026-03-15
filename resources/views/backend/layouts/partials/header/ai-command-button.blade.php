{{--
    AI Command Button - Triggers the Agentic CMS Modal
    This button appears in the header navbar and opens the AI command interface.
    Keyboard shortcut: Cmd/Ctrl + Shift + V to open with voice activation
--}}
<div
    x-data="{ aiModalOpen: false }"
    x-init="
        // Global keyboard shortcut: Cmd/Ctrl + Shift + V for voice command
        document.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.shiftKey && e.key.toLowerCase() === 'v') {
                e.preventDefault();
                aiModalOpen = true;
                $dispatch('ai-voice-activate');
            }
        });
    "
    @keydown.window.escape="aiModalOpen = false"
    @open-ai-modal.window="aiModalOpen = true"
>
    <x-tooltip title="{{ __('AI Agent') }} (⌘⇧V)" position="bottom">

    </x-tooltip>

    {{-- AI Command Modal --}}
    @include('components.modals.ai-command')
</div>
