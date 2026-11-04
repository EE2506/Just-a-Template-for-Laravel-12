<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { PanelLeftClose, PanelLeftOpen } from '@lucide/vue';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button'
import { useSidebar } from './utils';

const props = defineProps<{
    class?: HTMLAttributes['class'];
    label?: string;
}>();

const { isMobile, state, toggleSidebar } = useSidebar();
</script>

<template>
    <Button
        data-sidebar="trigger"
        data-slot="sidebar-trigger"
        variant="ghost"
        size="icon"
        :aria-label="props.label ?? 'Toggle sidebar'"
        :class="cn('h-7 w-7', props.class)"
        @click="toggleSidebar"
    >
        <PanelLeftOpen v-if="isMobile || state === 'collapsed'" />
        <PanelLeftClose v-else />
        <span
            :class="
                props.label
                    ? 'group-data-[collapsible=icon]:sr-only'
                    : 'sr-only'
            "
        >
            {{ props.label ?? 'Toggle sidebar' }}
        </span>
    </Button>
</template>
