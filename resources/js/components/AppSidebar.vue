<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, FolderGit2, LayoutGrid } from '@lucide/vue';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { useSidebar } from '@/components/ui/sidebar/utils';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const page = usePage();
const role = computed(() => page.props.auth.user?.role ?? 'user');
const { isMobile, state } = useSidebar();

const sidebarTheme = computed(() => {
    const themes = {
        developer: {
            '--sidebar-background': 'hsl(338 72% 20%)',
            '--sidebar-foreground': 'hsl(0 0% 98%)',
            '--sidebar-primary': 'hsl(338 78% 13%)',
            '--sidebar-primary-foreground': 'hsl(0 0% 100%)',
            '--sidebar-accent': 'hsl(338 55% 32%)',
            '--sidebar-accent-foreground': 'hsl(0 0% 100%)',
            '--sidebar-border': 'hsl(338 45% 38%)',
        },
        superadmin: {
            '--sidebar-background': 'hsl(16 90% 38%)',
            '--sidebar-foreground': 'hsl(0 0% 100%)',
            '--sidebar-primary': 'hsl(16 95% 30%)',
            '--sidebar-primary-foreground': 'hsl(0 0% 100%)',
            '--sidebar-accent': 'hsl(16 75% 47%)',
            '--sidebar-accent-foreground': 'hsl(0 0% 100%)',
            '--sidebar-border': 'hsl(16 70% 50%)',
        },
        admin: {
            '--sidebar-background': 'hsl(8 75% 36%)',
            '--sidebar-foreground': 'hsl(0 0% 100%)',
            '--sidebar-primary': 'hsl(8 85% 28%)',
            '--sidebar-primary-foreground': 'hsl(0 0% 100%)',
            '--sidebar-accent': 'hsl(8 62% 44%)',
            '--sidebar-accent-foreground': 'hsl(0 0% 100%)',
            '--sidebar-border': 'hsl(8 58% 48%)',
        },
    } as const;

    return role.value === 'user' ? {} : themes[role.value];
});

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar
        collapsible="icon"
        variant="inset"
        :data-role="role"
        :style="sidebarTheme"
    >
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
            <SidebarTrigger
                v-if="!isMobile"
                :label="
                    state === 'expanded'
                        ? 'Collapse navigation'
                        : 'Expand navigation'
                "
                class="mt-2 h-12 w-full justify-start gap-3 rounded-md border border-sidebar-border bg-sidebar-accent px-4 text-sidebar-accent-foreground group-data-[collapsible=icon]:size-12! group-data-[collapsible=icon]:w-full! group-data-[collapsible=icon]:justify-center! hover:bg-sidebar-accent/80 hover:text-sidebar-accent-foreground focus-visible:ring-2 focus-visible:ring-sidebar-ring"
            />
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

<style scoped>
:deep([data-role='admin'] [data-sidebar='sidebar']) {
    background: linear-gradient(145deg, hsl(18 90% 42%), hsl(0 78% 38%));
}
</style>
