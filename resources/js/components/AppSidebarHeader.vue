<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import DashboardAppearanceToggle from '@/components/DashboardAppearanceToggle.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { useSidebar } from '@/components/ui/sidebar/utils';
import type { BreadcrumbItem } from '@/types';

const { isMobile } = useSidebar();

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-sidebar-border/70 px-6 transition-[width] ease-linear md:px-4"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger v-if="isMobile" class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        <DashboardAppearanceToggle />
    </header>
</template>
