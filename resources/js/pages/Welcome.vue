<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { dashboard, login, register } from '@/routes';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Welcome" />

    <main class="min-h-screen bg-background text-foreground">
        <div class="mx-auto flex min-h-screen max-w-6xl flex-col px-6 py-6">
        <nav class="flex items-center justify-between">
            <div class="flex items-center gap-3 font-semibold">
                <span class="flex size-9 items-center justify-center rounded-md bg-primary text-primary-foreground">
                    <AppLogoIcon class="size-5" />
                </span>
                <span>LaraNote</span>
            </div>

            <div class="flex items-center gap-4 text-sm">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="font-medium text-muted-foreground hover:text-foreground"
                >
                    Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="login()"
                        class="font-medium text-muted-foreground hover:text-foreground"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="register()"
                        class="rounded-md bg-primary px-4 py-2 font-medium text-primary-foreground hover:bg-primary/90"
                    >
                        Register
                    </Link>
                </template>
            </div>
        </nav>

        <section class="grid flex-1 items-center gap-12 py-14 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="space-y-6">
                <div class="space-y-4">
                    <p class="text-sm font-medium text-primary">Your quiet writing space</p>
                    <h1 class="max-w-xl text-5xl font-semibold tracking-normal">
                        Simple notes, always ready.
                    </h1>
                    <p class="max-w-lg text-base leading-7 text-muted-foreground">
                        Write, organize, and revisit your notes in one clean workspace.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="rounded-md bg-primary px-5 py-2.5 font-medium text-primary-foreground hover:bg-primary/90"
                    >
                        Go to dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="login()"
                            class="rounded-md bg-primary px-5 py-2.5 font-medium text-primary-foreground hover:bg-primary/90"
                        >
                            Start writing
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="font-medium text-muted-foreground hover:text-foreground"
                        >
                            Create account
                        </Link>
                    </template>
                </div>
            </div>

            <div class="rounded-lg border bg-card p-3 shadow-sm">
                <div class="flex items-center gap-2 border-b px-2 pb-3">
                    <span class="size-2.5 rounded-full bg-red-400"></span>
                    <span class="size-2.5 rounded-full bg-amber-400"></span>
                    <span class="size-2.5 rounded-full bg-emerald-400"></span>
                </div>

                <div class="space-y-4 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground">Workspace</p>
                            <p class="text-lg font-semibold">Dashboard</p>
                        </div>
                        <AppLogoIcon class="size-7 text-primary" />
                    </div>

                    <div class="grid gap-3 sm:grid-cols-[1.2fr_0.8fr]">
                        <div class="rounded-md border bg-background p-4">
                            <p class="text-sm text-muted-foreground">Total notes</p>
                            <p class="mt-3 text-5xl font-semibold">12</p>
                            <p class="mt-4 text-sm text-muted-foreground">
                                Notes saved in your workspace.
                            </p>
                        </div>

                        <div class="rounded-md border bg-background p-4">
                            <p class="font-medium">Notes</p>
                            <p class="mt-2 text-sm leading-6 text-muted-foreground">
                                Edit, pin, search, and organize ideas.
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="rounded-md bg-sky-50 p-3 text-sky-950 dark:bg-sky-950 dark:text-sky-100">
                            <p class="text-sm font-medium">Project ideas</p>
                            <p class="mt-2 text-xs opacity-70">Updated today</p>
                        </div>

                        <div class="rounded-md bg-emerald-50 p-3 text-emerald-950 dark:bg-emerald-950 dark:text-emerald-100">
                            <p class="text-sm font-medium">Meeting notes</p>
                            <p class="mt-2 text-xs opacity-70">Pinned</p>
                        </div>

                        <div class="rounded-md bg-amber-50 p-3 text-amber-950 dark:bg-amber-950 dark:text-amber-100">
                            <p class="text-sm font-medium">Reading list</p>
                            <p class="mt-2 text-xs opacity-70">Draft</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </div>
    </main>
</template>
