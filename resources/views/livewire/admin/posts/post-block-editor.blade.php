<div class="space-y-6" x-data="{ sidebarOpen: true }">
    <!-- Top Floating Gutenberg Toolbar -->
    <div class="sticky top-4 z-40 rounded-2xl border border-zinc-800 bg-zinc-950/80 backdrop-blur-2xl p-3 shadow-2xl flex flex-wrap items-center justify-between gap-3">
        <!-- Left: Back, Type, & Title Preview -->
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.posts.index') }}"
               class="p-2 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-zinc-400 hover:text-white transition-colors border border-zinc-800">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider {{ $type === 'landing_page' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                    {{ str_replace('_', ' ', $type) }}
                </span>
                <span class="text-xs font-mono text-zinc-400 hidden sm:inline">/p/{{ $slug ?: 'untitled' }}</span>
            </div>
        </div>

        <!-- Center: Visual Editor vs Live Preview & Language Tabs -->
        <div class="flex items-center gap-2 bg-zinc-900/80 border border-zinc-800/80 rounded-xl p-1">
            <button type="button"
                    wire:click="$set('previewMode', false)"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 {{ ! $previewMode ? 'bg-orange-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.4)]' : 'text-zinc-400 hover:text-white' }}">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Editor</span>
            </button>
            <button type="button"
                    wire:click="$set('previewMode', true)"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 {{ $previewMode ? 'bg-orange-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.4)]' : 'text-zinc-400 hover:text-white' }}">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>Live Preview</span>
            </button>

            <span class="w-px h-4 bg-zinc-800 mx-1"></span>

            <!-- Language Switcher -->
            <button type="button"
                    wire:click="$set('locale', 'en')"
                    class="px-2 py-1 rounded text-xs font-mono font-bold {{ $locale === 'en' ? 'bg-zinc-800 text-orange-400' : 'text-zinc-500 hover:text-zinc-300' }}">
                EN
            </button>
            <button type="button"
                    wire:click="$set('locale', 'ar')"
                    class="px-2 py-1 rounded text-xs font-mono font-bold {{ $locale === 'ar' ? 'bg-zinc-800 text-orange-400' : 'text-zinc-500 hover:text-zinc-300' }}">
                AR
            </button>
        </div>

        <!-- Right: Add Block, Save Draft, Publish, Sidebar Toggle -->
        <div class="flex items-center gap-2">
            <button type="button"
                    wire:click="openBlockPicker()"
                    class="px-3 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-800 border border-zinc-800 text-orange-400 hover:text-orange-300 font-semibold text-xs transition-all flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Block</span>
            </button>

            <button type="button"
                    wire:click="save(false)"
                    wire:loading.attr="disabled"
                    class="px-3.5 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-800 border border-zinc-700 text-zinc-200 font-semibold text-xs transition-colors">
                <span wire:loading.remove wire:target="save(false)">Save Draft</span>
                <span wire:loading wire:target="save(false)">Saving...</span>
            </button>

            <button type="button"
                    wire:click="save(true)"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold text-xs shadow-[0_0_20px_rgba(249,115,22,0.35)] transition-all">
                <span wire:loading.remove wire:target="save(true)">Publish</span>
                <span wire:loading wire:target="save(true)">Publishing...</span>
            </button>

            <button type="button"
                    @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-xl bg-zinc-900 hover:bg-zinc-800 border border-zinc-800 text-zinc-400 hover:text-white transition-colors"
                    title="Toggle Inspector Sidebar">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Alert Flash -->
    @if(session()->has('success'))
        <div class="rounded-xl bg-emerald-500/10 border border-emerald-500/30 px-4 py-3 text-emerald-400 text-sm flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button @click="$el.parentElement.remove()" class="text-emerald-400 hover:text-white">&times;</button>
        </div>
    @endif

    <!-- Main Editor Grid (Canvas + Inspector Sidebar) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Center Canvas (Block Editor or Live Preview) -->
        <div :class="sidebarOpen ? 'lg:col-span-8 xl:col-span-9' : 'lg:col-span-12'" class="transition-all duration-300">
            @if($previewMode)
                <!-- LIVE PREVIEW CONTAINER -->
                <div class="rounded-3xl border border-zinc-800 bg-zinc-950 overflow-hidden shadow-2xl">
                    <div class="px-6 py-3 bg-zinc-900/90 border-b border-zinc-800 flex items-center justify-between text-xs font-mono text-zinc-400">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span>Live Preview Mode — /p/{{ $slug ?: 'preview' }}</span>
                        </div>
                        <span class="text-zinc-500">Locale: {{ strtoupper($locale) }}</span>
                    </div>

                    <div class="p-6 sm:p-12 min-h-[600px]">
                        @if(empty($blocks))
                            <div class="text-center py-20 text-zinc-600">
                                <p>No blocks added yet.</p>
                            </div>
                        @else
                            @foreach($blocks as $block)
                                <x-post-blocks.renderer :block="$block" />
                            @endforeach
                        @endif
                    </div>
                </div>
            @else
                <!-- VISUAL BLOCK BUILDER CANVAS -->
                <div class="space-y-6">
                    <!-- Document Title Canvas Card -->
                    <div class="rounded-3xl border border-zinc-800/80 bg-zinc-900/40 p-6 sm:p-8 backdrop-blur-xl shadow-xl">
                        <label class="block text-xs font-mono text-zinc-500 uppercase tracking-wider mb-2">
                            Document Title ({{ strtoupper($locale) }})
                        </label>
                        <input type="text"
                               wire:model.live.debounce.300ms="title.{{ $locale }}"
                               placeholder="Enter page or post title..."
                               class="w-full bg-transparent border-none p-0 text-2xl sm:text-4xl font-extrabold text-white placeholder-zinc-600 focus:outline-none focus:ring-0 leading-tight" />

                        @error('title.'.$locale)
                            <p class="text-xs text-red-400 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- BLOCKS TREE -->
                    <div class="space-y-4">
                        @foreach($blocks as $index => $block)
                            <div class="group relative rounded-3xl border border-zinc-800 bg-zinc-900/60 p-6 shadow-xl backdrop-blur-xl transition-all duration-200 hover:border-zinc-700">
                                <!-- Block Header & Controls -->
                                <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-800/60">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                                        <span class="text-xs font-mono font-bold uppercase tracking-wider text-orange-400">
                                            {{ str_replace('_', ' ', $block['type']) }} Block #{{ $index + 1 }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-1.5 opacity-90 group-hover:opacity-100">
                                        <button type="button"
                                                wire:click="moveBlockUp({{ $index }})"
                                                title="Move Up"
                                                class="p-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-400 hover:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                            </svg>
                                        </button>
                                        <button type="button"
                                                wire:click="moveBlockDown({{ $index }})"
                                                title="Move Down"
                                                class="p-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-400 hover:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <button type="button"
                                                wire:click="duplicateBlock({{ $index }})"
                                                title="Duplicate Block"
                                                class="p-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-400 hover:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                        <button type="button"
                                                wire:click="removeBlock({{ $index }})"
                                                title="Delete Block"
                                                class="p-1.5 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Dynamic Inline Inputs based on Block Type -->
                                <div class="space-y-4">
                                    @switch($block['type'])
                                        @case('hero')
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="md:col-span-2">
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Badge</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.badge" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500" />
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Headline</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.headline" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-base font-bold focus:outline-none focus:border-orange-500" />
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Subtitle</label>
                                                    <textarea wire:model.live.debounce.300ms="blocks.{{ $index }}.content.subtitle" rows="2" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-300 text-sm focus:outline-none focus:border-orange-500"></textarea>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Primary CTA Button</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.cta_primary_text" placeholder="Button text" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500" />
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Primary CTA URL</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.cta_primary_url" placeholder="/register" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500" />
                                                </div>
                                            </div>
                                            @break

                                        @case('heading')
                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                                                <div class="md:col-span-1">
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Level</label>
                                                    <select wire:model.live="blocks.{{ $index }}.content.level" class="w-full px-3 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500">
                                                        <option value="h1">H1 - Main</option>
                                                        <option value="h2">H2 - Section</option>
                                                        <option value="h3">H3 - Sub</option>
                                                        <option value="h4">H4 - Minor</option>
                                                    </select>
                                                </div>
                                                <div class="md:col-span-3">
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Heading Text</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.text" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-base font-bold focus:outline-none focus:border-orange-500" />
                                                </div>
                                            </div>
                                            @break

                                        @case('paragraph')
                                            <div>
                                                <label class="block text-xs font-mono text-zinc-400 mb-1">Paragraph Text</label>
                                                <textarea wire:model.live.debounce.300ms="blocks.{{ $index }}.content.text" rows="4" class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm leading-relaxed focus:outline-none focus:border-orange-500"></textarea>
                                            </div>
                                            @break

                                        @case('image')
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="md:col-span-2">
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Image URL</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.url" placeholder="https://..." class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500" />
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Caption</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.caption" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500" />
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Alt Text</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.alt" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500" />
                                                </div>
                                            </div>
                                            @break

                                        @case('features_grid')
                                            <div class="space-y-4">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <div class="md:col-span-2">
                                                        <label class="block text-xs font-mono text-zinc-400 mb-1">Grid Headline</label>
                                                        <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.headline" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-mono text-zinc-400 mb-1">Columns</label>
                                                        <select wire:model.live="blocks.{{ $index }}.content.columns" class="w-full px-3 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500">
                                                            <option value="2">2 Columns</option>
                                                            <option value="3">3 Columns</option>
                                                            <option value="4">4 Columns</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="space-y-3 pt-2">
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-xs font-mono text-zinc-400 font-bold uppercase">Feature Cards</span>
                                                        <button type="button" wire:click="addFeatureItem({{ $index }})" class="px-2.5 py-1 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-orange-400 text-xs font-semibold">
                                                            + Add Card
                                                        </button>
                                                    </div>

                                                    @foreach(($block['content']['items'] ?? []) as $cardIndex => $card)
                                                        <div class="p-3.5 rounded-xl bg-zinc-950 border border-zinc-800/80 flex items-start gap-3">
                                                            <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                                                                <div>
                                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.items.{{ $cardIndex }}.title" placeholder="Card Title" class="w-full px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-200 text-xs font-semibold" />
                                                                </div>
                                                                <div class="md:col-span-2">
                                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.items.{{ $cardIndex }}.description" placeholder="Card Description" class="w-full px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-400 text-xs" />
                                                                </div>
                                                            </div>
                                                            <button type="button" wire:click="removeFeatureItem({{ $index }}, {{ $cardIndex }})" class="text-zinc-500 hover:text-red-400 p-1">
                                                                &times;
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @break

                                        @case('cta_banner')
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="md:col-span-2">
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Headline</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.headline" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500" />
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Description</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.description" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-300 text-sm focus:outline-none focus:border-orange-500" />
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Button Text</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.button_text" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500" />
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Button URL</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.button_url" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500" />
                                                </div>
                                            </div>
                                            @break

                                        @case('faq')
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">FAQ Section Title</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.headline" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500" />
                                                </div>

                                                <div class="space-y-3 pt-2">
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-xs font-mono text-zinc-400 font-bold uppercase">Questions & Answers</span>
                                                        <button type="button" wire:click="addFaqItem({{ $index }})" class="px-2.5 py-1 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-orange-400 text-xs font-semibold">
                                                            + Add FAQ
                                                        </button>
                                                    </div>

                                                    @foreach(($block['content']['items'] ?? []) as $faqIndex => $faq)
                                                        <div class="p-3.5 rounded-xl bg-zinc-950 border border-zinc-800/80 space-y-2 relative">
                                                            <div class="flex items-center justify-between">
                                                                <span class="text-[11px] font-mono text-zinc-500">Q #{{ $faqIndex + 1 }}</span>
                                                                <button type="button" wire:click="removeFaqItem({{ $index }}, {{ $faqIndex }})" class="text-zinc-500 hover:text-red-400 text-xs">
                                                                    Remove
                                                                </button>
                                                            </div>
                                                            <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.items.{{ $faqIndex }}.question" placeholder="Question" class="w-full px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-200 text-xs font-semibold" />
                                                            <textarea wire:model.live.debounce.300ms="blocks.{{ $index }}.content.items.{{ $faqIndex }}.answer" rows="2" placeholder="Answer..." class="w-full px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs"></textarea>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @break

                                        @case('quote')
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Quote</label>
                                                    <textarea wire:model.live.debounce.300ms="blocks.{{ $index }}.content.quote" rows="2" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm focus:outline-none focus:border-orange-500"></textarea>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-xs font-mono text-zinc-400 mb-1">Author</label>
                                                        <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.author" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-mono text-zinc-400 mb-1">Citation / Role</label>
                                                        <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.citation" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm" />
                                                    </div>
                                                </div>
                                            </div>
                                            @break

                                        @case('code')
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Language</label>
                                                    <input type="text" wire:model.live.debounce.300ms="blocks.{{ $index }}.content.language" placeholder="bash, php, javascript" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm" />
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-mono text-zinc-400 mb-1">Code Snippet</label>
                                                    <textarea wire:model.live.debounce.300ms="blocks.{{ $index }}.content.code" rows="4" class="w-full px-3.5 py-2 font-mono text-emerald-400 rounded-xl bg-zinc-950 border border-zinc-800 text-xs focus:outline-none focus:border-orange-500"></textarea>
                                                </div>
                                            </div>
                                            @break
                                    @endswitch
                                </div>

                                <!-- Insert Block Here Divider -->
                                <div class="mt-4 pt-3 text-center">
                                    <button type="button"
                                            wire:click="openBlockPicker({{ $index }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-zinc-950 hover:bg-zinc-800 border border-dashed border-zinc-700 hover:border-orange-500 text-[11px] font-mono text-zinc-400 hover:text-orange-400 transition-all">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Insert block here</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Bottom Add Block Button -->
                    <div class="text-center py-6">
                        <button type="button"
                                wire:click="openBlockPicker()"
                                class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-zinc-900 hover:bg-zinc-800 border border-zinc-700 hover:border-orange-500 text-sm font-semibold text-zinc-200 hover:text-white transition-all shadow-xl">
                            <svg class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add Another Block</span>
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Inspector Sidebar -->
        <div x-show="sidebarOpen"
             class="lg:col-span-4 xl:col-span-3 space-y-6">
            <div class="rounded-3xl border border-zinc-800 bg-zinc-900/60 p-6 shadow-xl backdrop-blur-xl space-y-5">
                <h3 class="text-sm font-mono font-bold uppercase tracking-wider text-white flex items-center justify-between">
                    <span>Document Settings</span>
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                </h3>

                <!-- Slug -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-mono text-zinc-400">URL Slug</label>
                        <button type="button" wire:click="generateSlug" class="text-[10px] font-mono text-orange-400 hover:underline">
                            Auto Generate
                        </button>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-zinc-500 text-xs font-mono">/p/</span>
                        <input type="text"
                               wire:model.live.debounce.300ms="slug"
                               class="w-full pl-8 pr-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-xs font-mono focus:outline-none focus:border-orange-500" />
                    </div>
                    @error('slug') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Page Type -->
                <div>
                    <label class="block text-xs font-mono text-zinc-400 mb-1">Type</label>
                    <select wire:model.live="type" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-xs focus:outline-none focus:border-orange-500">
                        <option value="landing_page">Landing Page</option>
                        <option value="post">Blog Post / Article</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-mono text-zinc-400 mb-1">Publication Status</label>
                    <select wire:model.live="status" class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-xs focus:outline-none focus:border-orange-500">
                        <option value="draft">Draft (Private)</option>
                        <option value="published">Published (Live)</option>
                    </select>
                </div>

                <!-- Featured Image -->
                <div>
                    <label class="block text-xs font-mono text-zinc-400 mb-1">Featured Image URL</label>
                    <input type="text"
                           wire:model.live.debounce.300ms="featuredImage"
                           placeholder="https://images.unsplash.com/..."
                           class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-200 text-xs focus:outline-none focus:border-orange-500" />
                    @if($featuredImage)
                        <div class="mt-2 rounded-xl overflow-hidden aspect-video bg-zinc-950 border border-zinc-800">
                            <img src="{{ $featuredImage }}" alt="Featured" class="w-full h-full object-cover" />
                        </div>
                    @endif
                </div>

                <!-- Excerpt -->
                <div>
                    <label class="block text-xs font-mono text-zinc-400 mb-1">Excerpt ({{ strtoupper($locale) }})</label>
                    <textarea wire:model.live.debounce.300ms="excerpt.{{ $locale }}"
                              rows="3"
                              placeholder="Brief summary for listings and cards..."
                              class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-300 text-xs leading-relaxed focus:outline-none focus:border-orange-500"></textarea>
                </div>

                <!-- SEO Meta -->
                <div class="border-t border-zinc-800/80 pt-4 space-y-3">
                    <h4 class="text-xs font-mono font-bold text-zinc-300 uppercase">SEO & Social Meta</h4>
                    <div>
                        <label class="block text-[11px] font-mono text-zinc-500 mb-1">Meta Title</label>
                        <input type="text" wire:model.live.debounce.300ms="seoMeta.meta_title" placeholder="SEO Title tag" class="w-full px-3 py-1.5 rounded-lg bg-zinc-950 border border-zinc-800 text-zinc-300 text-xs focus:outline-none focus:border-orange-500" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-mono text-zinc-500 mb-1">Meta Description</label>
                        <textarea wire:model.live.debounce.300ms="seoMeta.meta_description" rows="2" placeholder="Search engine description..." class="w-full px-3 py-1.5 rounded-lg bg-zinc-950 border border-zinc-800 text-zinc-400 text-xs focus:outline-none focus:border-orange-500"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gutenberg Block Picker Modal -->
    @if($showBlockPicker)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
             @keydown.escape.window="$wire.closeBlockPicker()">
            <div class="relative w-full max-w-2xl rounded-3xl border border-zinc-800 bg-zinc-900 p-6 sm:p-8 shadow-2xl space-y-6"
                 @click.away="$wire.closeBlockPicker()">
                <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span>Add Block</span>
                            <span class="text-xs font-mono px-2 py-0.5 rounded bg-orange-500/10 text-orange-400 border border-orange-500/20">Gutenberg Library</span>
                        </h3>
                        <p class="text-xs text-zinc-400 mt-0.5">Select a block component to insert into your layout.</p>
                    </div>
                    <button type="button" wire:click="closeBlockPicker" class="text-zinc-500 hover:text-white p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Grid of Block Types -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 max-h-[60vh] overflow-y-auto pr-1">
                    <!-- Hero -->
                    <button type="button" wire:click="selectBlock('hero')" class="p-4 rounded-2xl bg-zinc-950 border border-zinc-800 hover:border-orange-500 hover:bg-zinc-800/60 text-left transition-all group">
                        <div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <h4 class="text-xs font-bold text-white">Hero Banner</h4>
                        <p class="text-[11px] text-zinc-500 mt-1">High-impact landing hero with CTA buttons</p>
                    </button>

                    <!-- Heading -->
                    <button type="button" wire:click="selectBlock('heading')" class="p-4 rounded-2xl bg-zinc-950 border border-zinc-800 hover:border-orange-500 hover:bg-zinc-800/60 text-left transition-all group">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/></svg>
                        </div>
                        <h4 class="text-xs font-bold text-white">Heading</h4>
                        <p class="text-[11px] text-zinc-500 mt-1">H1, H2, H3, H4 section titles</p>
                    </button>

                    <!-- Paragraph -->
                    <button type="button" wire:click="selectBlock('paragraph')" class="p-4 rounded-2xl bg-zinc-950 border border-zinc-800 hover:border-orange-500 hover:bg-zinc-800/60 text-left transition-all group">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h12"/></svg>
                        </div>
                        <h4 class="text-xs font-bold text-white">Paragraph</h4>
                        <p class="text-[11px] text-zinc-500 mt-1">Body text and article paragraphs</p>
                    </button>

                    <!-- Image -->
                    <button type="button" wire:click="selectBlock('image')" class="p-4 rounded-2xl bg-zinc-950 border border-zinc-800 hover:border-orange-500 hover:bg-zinc-800/60 text-left transition-all group">
                        <div class="w-8 h-8 rounded-lg bg-pink-500/10 text-pink-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h4 class="text-xs font-bold text-white">Media Image</h4>
                        <p class="text-[11px] text-zinc-500 mt-1">Visual graphic with caption</p>
                    </button>

                    <!-- Features Grid -->
                    <button type="button" wire:click="selectBlock('features_grid')" class="p-4 rounded-2xl bg-zinc-950 border border-zinc-800 hover:border-orange-500 hover:bg-zinc-800/60 text-left transition-all group">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        </div>
                        <h4 class="text-xs font-bold text-white">Features Grid</h4>
                        <p class="text-[11px] text-zinc-500 mt-1">Multi-column cards for key benefits</p>
                    </button>

                    <!-- CTA Banner -->
                    <button type="button" wire:click="selectBlock('cta_banner')" class="p-4 rounded-2xl bg-zinc-950 border border-zinc-800 hover:border-orange-500 hover:bg-zinc-800/60 text-left transition-all group">
                        <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        </div>
                        <h4 class="text-xs font-bold text-white">CTA Banner</h4>
                        <p class="text-[11px] text-zinc-500 mt-1">High conversion lead generator</p>
                    </button>

                    <!-- FAQ -->
                    <button type="button" wire:click="selectBlock('faq')" class="p-4 rounded-2xl bg-zinc-950 border border-zinc-800 hover:border-orange-500 hover:bg-zinc-800/60 text-left transition-all group">
                        <div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="text-xs font-bold text-white">FAQ Accordion</h4>
                        <p class="text-[11px] text-zinc-500 mt-1">Collapsible questions and answers</p>
                    </button>

                    <!-- Quote -->
                    <button type="button" wire:click="selectBlock('quote')" class="p-4 rounded-2xl bg-zinc-950 border border-zinc-800 hover:border-orange-500 hover:bg-zinc-800/60 text-left transition-all group">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        </div>
                        <h4 class="text-xs font-bold text-white">Quote / Testimonial</h4>
                        <p class="text-[11px] text-zinc-500 mt-1">Highlighted quote with author citation</p>
                    </button>

                    <!-- Code -->
                    <button type="button" wire:click="selectBlock('code')" class="p-4 rounded-2xl bg-zinc-950 border border-zinc-800 hover:border-orange-500 hover:bg-zinc-800/60 text-left transition-all group">
                        <div class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-400 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <h4 class="text-xs font-bold text-white">Code Snippet</h4>
                        <p class="text-[11px] text-zinc-500 mt-1">Preformatted syntax terminal block</p>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
