<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Button } from '@/components/ui/button';
import { home, login } from '@/routes';

const props = defineProps<{
    status: number;
    label: string;
    retryAfter: number | null;
}>();

const details = computed(() => {
    const knownErrors: Record<number, { title: string; description: string }> =
        {
            400: {
                title: 'We could not process that request',
                description: 'Check the request and try again.',
            },
            401: {
                title: 'Sign in to continue',
                description:
                    'You may need to sign in before you can access this page.',
            },
            403: {
                title: 'You do not have access to this page',
                description:
                    'If you think you should have access, contact the site administrator.',
            },
            404: {
                title: 'We could not find that page',
                description:
                    'The page may have moved, or the address may be incorrect.',
            },
            408: {
                title: 'That request took too long',
                description: 'Check your connection, then try again.',
            },
            409: {
                title: 'This request conflicts with the current state',
                description: 'Refresh the page and try your action again.',
            },
            410: {
                title: 'This page is no longer available',
                description: 'The requested content has been removed.',
            },
            419: {
                title: 'Your session may have expired',
                description:
                    'Return to the previous page and try again. You may need to sign in.',
            },
            422: {
                title: 'We could not process that request',
                description: 'Review the information and try again.',
            },
            429: {
                title: 'You are making requests too quickly',
                description: 'Please wait a moment before trying again.',
            },
            500: {
                title: 'Something went wrong',
                description:
                    'An unexpected problem occurred on our side. Please try again later.',
            },
            501: {
                title: 'This action is not available',
                description: 'The server does not support this request.',
            },
            502: {
                title: 'The service is temporarily unavailable',
                description: 'Please try again in a little while.',
            },
            503: {
                title: 'We are temporarily unavailable',
                description:
                    'The service may be busy or undergoing maintenance. Please try again later.',
            },
            504: {
                title: 'The server took too long to respond',
                description: 'Please try again in a little while.',
            },
        };

    return (
        knownErrors[props.status] ?? {
            title:
                props.status >= 500
                    ? 'Something went wrong'
                    : 'We could not complete that request',
            description:
                props.status >= 500
                    ? 'An unexpected problem occurred on our side. Please try again later.'
                    : 'Check the request and try again.',
        }
    );
});

const retryMessage = computed(() => {
    if (props.status !== 429 || props.retryAfter === null) {
        return null;
    }

    const seconds = Math.max(1, props.retryAfter);

    return `You can try again in about ${seconds} ${seconds === 1 ? 'second' : 'seconds'}.`;
});
</script>

<template>
    <Head :title="`${status} ${label}`" />

    <AuthLayout :title="details.title" :description="details.description">
        <div class="flex flex-col gap-6">
            <p
                class="mx-auto rounded-full border border-border bg-muted px-4 py-1.5 text-sm font-medium text-muted-foreground"
                role="status"
            >
                {{ status }} <span aria-hidden="true">·</span> {{ label }}
            </p>

            <p
                v-if="retryMessage"
                class="text-center text-sm font-medium text-muted-foreground"
            >
                {{ retryMessage }}
            </p>

            <div class="grid gap-3 sm:grid-cols-2">
                <Button as-child>
                    <Link :href="home()">Return home</Link>
                </Button>
                <Button variant="outline" as-child>
                    <Link :href="login()">Go to login</Link>
                </Button>
            </div>
        </div>
    </AuthLayout>
</template>
