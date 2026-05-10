<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        :class="className"
        v-bind="$attrs"
        fill="none"
    >
        <path
            d="M5 3.75h9.25L19 8.5v11.75H5V3.75Z"
            stroke="currentColor"
            stroke-width="1.8"
            stroke-linejoin="round"
        />
        <path
            d="M14 4v5h5M8 9h3.5M8 12.5h5"
            stroke="currentColor"
            stroke-width="1.8"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
        <path
            d="m9 18 1.05-3.15 5.45-5.45a1.49 1.49 0 0 1 2.1 2.1l-5.45 5.45L9 18Z"
            fill="currentColor"
        />
    </svg>
</template>
