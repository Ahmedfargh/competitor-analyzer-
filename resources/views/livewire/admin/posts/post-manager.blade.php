<div class="space-y-8" x-data>
    <!-- Header with Breadcrumbs & Action -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-zinc-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-zinc-300">Admin</a>
                <span>/</span>
                <span class="text-orange-400">Content Management</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
                <span>Landing Pages & Posts</span>
                <span class="text-xs px-2.5 py-1 rounded-full bg-orange-500/10 border border-orange-500/30 text-orange-400 font-mono">
                    Gutenberg Blocks
                </span>
            </h1>
            <p class="text-sm text-zinc-400 mt-1 font-light">
                Design modern landing pages and publish rich blog posts with Gutenberg-style visual block editing.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.posts.create', ['type' => 'landing_page']) }}"
               class="px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs transition-all duration-200 flex items-center gap-2 shadow-[0_0_20px_rgba(249,115,22,0.3)]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>New Landing Page</span>
            </a>
            <a href="{{ route('admin.posts.create', ['type' => 'post']) }}"
               class="px-4 py-2.5 rounded-xl bg-zinc-900 hover:bg-zinc-800 text-zinc-300 hover:text-white border border-zinc-800 font-semibold text-xs transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>New Post</span>
            </a>
        </div>
    </div>

    <!-- Alert Notification -->
    @if(session()->has('success'))
        <div class="rounded-xl bg-emerald-500/10 border border-emerald-500/30 px-4 py-3 text-emerald-400 text-sm flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button @click="$el.parentElement.remove()" class="text-emerald-400 hover:text-white">&times;</button>
        </div>
    @endif

    <!-- Search and Filters Bar -->
    <div class="rounded-2xl border border-zinc-800 bg-zinc-900/40 p-4 backdrop-blur-xl flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">
        <div class="relative flex-1">
            <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Search title, slug, or content..."
                   class="w-full pl-10 pr-4 py-2 rounded-xl bg-zinc-950/70 border border-zinc-800 text-zinc-200 placeholder-zinc-500 text-sm focus:outline-none focus:border-orange-500 transition-colors" />
        </div>

        <div class="flex items-center gap-3">
            <select wire:model.live="typeFilter" class="px-3 py-2 rounded-xl bg-zinc-950/70 border border-zinc-800 text-zinc-300 text-sm focus:outline-none focus:border-orange-500">
                <option value="">All Types</option>
                <option value="landing_page">Landing Pages</option>
                <option value="post">Posts / Articles</option>
            </select>

            <select wire:model.live="statusFilter" class="px-3 py-2 rounded-xl bg-zinc-950/70 border border-zinc-800 text-zinc-300 text-sm focus:outline-none focus:border-orange-500">
                <option value="">All Statuses</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
        </div>
    </div>

    <!-- Posts Grid / Table -->
    <div class="rounded-2xl border border-zinc-800 bg-zinc-900/40 overflow-hidden shadow-2xl backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-800/80 bg-zinc-900/60 text-[11px] font-mono text-zinc-400 uppercase tracking-wider">
                        <th class="px-6 py-3.5">Title & Slug</th>
                        <th class="px-6 py-3.5">Type</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Blocks</th>
                        <th class="px-6 py-3.5">Author</th>
                        <th class="px-6 py-3.5">Updated</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/50 text-sm">
                    @forelse($posts as $post)
                        @php
                            $titleEn = $post->getTranslation('title', 'en', false);
                            $titleAr = $post->getTranslation('title', 'ar', false);
                            $displayTitle = $titleEn ?: ($titleAr ?: 'Untitled');
                            $blocksCount = is_array($post->blocks) ? count($post->blocks) : 0;
                        @endphp
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-white flex items-center gap-2">
                                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="hover:text-orange-400 transition-colors">
                                        {{ $displayTitle }}
                                    </a>
                                </div>
                                <div class="text-xs text-zinc-500 font-mono mt-0.5">
                                    /p/{{ $post->slug }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-mono font-semibold uppercase tracking-wider {{ $post->type === 'landing_page' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                                    {{ str_replace('_', ' ', $post->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button type="button"
                                        wire:click="togglePublish({{ $post->id }})"
                                        class="px-2.5 py-1 rounded-full text-[11px] font-mono font-semibold uppercase tracking-wider transition-all {{ $post->status === 'published' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/20' : 'bg-zinc-800 text-zinc-400 border border-zinc-700 hover:bg-zinc-700' }}">
                                    {{ $post->status }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-zinc-400">
                                <span class="px-2 py-0.5 rounded bg-zinc-950 border border-zinc-800 text-zinc-300">
                                    {{ $blocksCount }} blocks
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-zinc-400 font-mono">
                                {{ $post->author?->name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-zinc-500 font-mono">
                                {{ $post->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ url('/p/' . $post->slug) }}"
                                       target="_blank"
                                       title="View Public Page"
                                       class="p-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-zinc-400 hover:text-white transition-colors border border-zinc-800">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    <button type="button"
                                            wire:click="duplicate({{ $post->id }})"
                                            title="Duplicate"
                                            class="p-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-zinc-400 hover:text-white transition-colors border border-zinc-800">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                    <a href="{{ route('admin.posts.edit', $post->id) }}"
                                       title="Edit in Block Editor"
                                       class="p-2 rounded-lg bg-orange-500/10 hover:bg-orange-500 text-orange-400 hover:text-white transition-colors border border-orange-500/20">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <button type="button"
                                            wire:click="delete({{ $post->id }})"
                                            wire:confirm="Are you sure you want to delete this page/post?"
                                            title="Delete"
                                            class="p-2 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white transition-colors border border-red-500/20">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-zinc-500 font-light">
                                <div class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800 flex items-center justify-center mx-auto mb-3 text-zinc-600">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <p class="text-base text-zinc-400 font-medium">No pages or posts found.</p>
                                <p class="text-xs text-zinc-600 mt-1">Get started by creating your first landing page or blog article.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
            <div class="px-6 py-4 border-t border-zinc-800 bg-zinc-900/60">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
