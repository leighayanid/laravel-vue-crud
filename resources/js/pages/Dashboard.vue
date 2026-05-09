<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <Link href="/notes" class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-blue-50 dark:bg-blue-900 flex items-center justify-center hover:bg-blue-100 dark:hover:bg-blue-800 transition">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">📝</div>
                    <div class="text-sm font-semibold text-blue-700 dark:text-blue-300 mt-2">My Notes</div>
                </div>
            </Link>
            <div class="w-full h-full">
                <PlaceholderPattern
                    title="Create new file"
                    description="Start a new project or jot down ideas with a fresh file."
                    icon="📄"
                />
                <Button as-child>
                    <Link href="/notes" class="absolute inset-0" />
                </Button>
            </div>
            </div>
    </div>
</template>
