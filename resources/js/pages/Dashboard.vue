<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, FileText, NotebookPen, Plus } from 'lucide-vue-next';
import { dashboard } from '@/routes';
import { index } from '@/routes/notes';

defineProps<{
    notesCount: number;
}>();

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

    <div class="flex h-full flex-1 flex-col gap-5 overflow-x-auto rounded-xl p-5">
        <header class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">

            <Link
                :href="index()"
                class="inline-flex h-9 w-fit items-center justify-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition hover:bg-primary/90"
            >
                <Plus class="size-4" />
                New note
            </Link>
        </header>

        <section class="grid gap-4 md:grid-cols-[minmax(0,1.35fr)_minmax(260px,0.65fr)]">
            <Link
                :href="index()"
                class="group relative overflow-hidden rounded-lg border border-sidebar-border/70 bg-card p-6 text-card-foreground shadow-xs transition hover:border-primary/40 hover:shadow-sm dark:border-sidebar-border"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-4">
                        <div class="flex size-11 items-center justify-center rounded-md bg-primary/10 text-primary">
                            <FileText class="size-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Total notes</p>
                            <p class="mt-2 text-5xl font-semibold tracking-normal">{{ notesCount }}</p>
                        </div>
                    </div>

                    <ArrowRight class="size-5 text-muted-foreground transition group-hover:translate-x-1 group-hover:text-primary" />
                </div>

                <p class="mt-6 text-sm text-muted-foreground">
                    {{ notesCount === 1 ? '1 note saved in your workspace.' : `${notesCount} notes saved in your workspace.` }}
                </p>
            </Link>

            <div class="rounded-lg border border-sidebar-border/70 bg-card p-6 shadow-xs dark:border-sidebar-border">
                <div class="flex size-11 items-center justify-center rounded-md bg-sky-500/10 text-sky-600 dark:text-sky-400">
                    <NotebookPen class="size-5" />
                </div>

                <div class="mt-5 space-y-2">
                    <h2 class="text-base font-semibold tracking-normal">Notes</h2>
                    <p class="text-sm text-muted-foreground">
                        Open your notes list to edit, pin, search, and organize your ideas.
                    </p>
                </div>

                <Link
                    :href="index()"
                    class="mt-5 inline-flex items-center gap-2 text-sm font-medium text-primary hover:underline"
                >
                    View notes
                    <ArrowRight class="size-4" />
                </Link>
            </div>
        </section>
    </div>
</template>
