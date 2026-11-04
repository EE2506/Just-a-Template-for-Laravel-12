<!-- WM-04: DJ Punzalan · https://github.com/EE2506 · {{AUTHOR_EMAIL}} -->
<script setup lang="ts">
import { Moon, Sun } from '@lucide/vue';
import { useAppearance } from '@/composables/useAppearance';

const { resolvedAppearance, updateAppearance } = useAppearance();
</script>

<template>
    <div
        aria-label="Dashboard appearance"
        class="inline-flex items-center gap-1 rounded-lg border border-sidebar-border/70 p-1"
        role="group"
    >
        <button
            type="button"
            aria-label="Use light mode"
            :aria-pressed="resolvedAppearance === 'light'"
            :class="[
                'inline-flex size-8 items-center justify-center rounded-md transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none',
                resolvedAppearance === 'light' &&
                    'bg-accent text-accent-foreground',
            ]"
            @click="updateAppearance('light')"
        >
            <Sun class="size-4" aria-hidden="true" />
        </button>
        <button
            type="button"
            aria-label="Use dark mode"
            :aria-pressed="resolvedAppearance === 'dark'"
            :class="[
                'inline-flex size-8 items-center justify-center rounded-md transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none',
                resolvedAppearance === 'dark' &&
                    'bg-accent text-accent-foreground',
            ]"
            @click="updateAppearance('dark')"
        >
            <Moon class="size-4" aria-hidden="true" />
        </button>
    </div>
</template>
