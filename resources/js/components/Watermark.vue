<!-- WM-04: DJ Punzalan · https://github.com/EE2506 · {{AUTHOR_EMAIL}} -->
<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const watermark = usePage().props.watermark;
const hasAuthorUrl = computed(() => /^https?:\/\/.+/.test(watermark.url));
const hasAuthorEmail = computed(() =>
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(watermark.email),
);
</script>

<template>
    <footer
        class="flex flex-wrap items-center justify-center gap-x-2 gap-y-1 px-4 py-3 text-center text-xs text-muted-foreground"
    >
        <a
            v-if="hasAuthorUrl"
            :href="watermark.url"
            target="_blank"
            rel="noopener noreferrer"
            class="underline underline-offset-4"
        >
            {{ watermark.name }}
        </a>
        <span v-else>{{ watermark.name }}</span>
        <span>© {{ watermark.year }}</span>
        <a
            v-if="hasAuthorEmail"
            :href="`mailto:${watermark.email}`"
            class="underline underline-offset-4"
        >
            {{ watermark.email }}
        </a>
        <span v-if="watermark.license">{{ watermark.license }}</span>
    </footer>
</template>
