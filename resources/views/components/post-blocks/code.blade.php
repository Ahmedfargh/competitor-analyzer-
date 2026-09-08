@props(['content' => []])

@php
    $language = $content['language'] ?? 'bash';
    $code = $content['code'] ?? '';
@endphp

<div class="my-8 rounded-2xl border border-zinc-800 bg-zinc-950 overflow-hidden shadow-2xl">
    <div class="flex items-center justify-between px-4 py-2.5 bg-zinc-900/80 border-b border-zinc-800 text-xs font-mono text-zinc-400">
        <span class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-red-500/80"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-yellow-500/80"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-green-500/80"></span>
            <span class="ml-2 text-zinc-500">{{ $language }}</span>
        </span>
    </div>
    <pre class="p-5 text-sm font-mono text-emerald-400 overflow-x-auto leading-relaxed"><code>{{ $code }}</code></pre>
</div>
