import { NoteColor } from '@/types';

export const colorOptions: Array<{ label: string; value: NoteColor }> = [
    { label: 'Slate', value: 'slate' },
    { label: 'Sky', value: 'sky' },
    { label: 'Emerald', value: 'emerald' },
    { label: 'Amber', value: 'amber' },
    { label: 'Rose', value: 'rose' },
    { label: 'Violet', value: 'violet' },
    { label: 'Fuchsia', value: 'fuchsia' },
    { label: 'Pink', value: 'pink' },
    { label: 'Indigo', value: 'indigo' },
    { label: 'Orange', value: 'orange' },
    { label: 'Yellow', value: 'yellow' },
    { label: 'Red', value: 'red' },
    { label: 'Gray', value: 'gray' },
    { label: 'Zinc', value: 'zinc' },
    { label: 'Neutral', value: 'neutral' },
    { label: 'Stone', value: 'stone' },
    { label: 'Green', value: 'green' },
    { label: 'Blue', value: 'blue' },
    { label: 'Purple', value: 'purple' }
];

export const colorStyles: Record<
    NoteColor,
    { dot: string; note: string; swatch: string }
> = {
    slate: {
        dot: 'bg-slate-500',
        note: 'border-l-slate-500',
        swatch: 'bg-slate-500',
    },
    sky: {
        dot: 'bg-sky-500',
        note: 'border-l-sky-500',
        swatch: 'bg-sky-500',
    },
    emerald: {
        dot: 'bg-emerald-500',
        note: 'border-l-emerald-500',
        swatch: 'bg-emerald-500',
    },
    amber: {
        dot: 'bg-amber-500',
        note: 'border-l-amber-500',
        swatch: 'bg-amber-500',
    },
    rose: {
        dot: 'bg-rose-500',
        note: 'border-l-rose-500',
        swatch: 'bg-rose-500',
    },
    violet: {
        dot: 'bg-violet-500',
        note: 'border-l-violet-500',
        swatch: 'bg-violet-500',
    },
    fuchsia: {
        dot: 'bg-fuchsia-500',
        note: 'border-l-fuchsia-500',
        swatch: 'bg-fuchsia-500',
    },
    indigo: {
        dot: 'bg-indigo-500',
        note: 'border-l-indigo-500',
        swatch: 'bg-indigo-500',
    },
    orange: {
        dot: 'bg-orange-500',
        note: 'border-l-orange-500',
        swatch: 'bg-orange-500',
    },
    yellow: {
        dot: 'bg-yellow-500',
        note: 'border-l-yellow-500',
        swatch: 'bg-yellow-500',
    },
    red: {
        dot: 'bg-red-500',
        note: 'border-l-red-500',
        swatch: 'bg-red-500',
    },
    pink: {
        dot: 'bg-pink-500',
        note: 'border-l-pink-500',
        swatch: 'bg-pink-500',
    },
    gray: {
        dot: 'bg-gray-500',
        note: 'border-l-gray-500',
        swatch: 'bg-gray-500',
    },
    zinc: {
        dot: '  bg-zinc-500',
        note: 'border-l-zinc-500',
        swatch: 'bg-zinc-500',
    },
    neutral: {
        dot: 'bg-neutral-500',
        note: 'border-l-neutral-500',
        swatch: 'bg-neutral-500',
    },
    stone: {
        dot: 'bg-stone-500',
        note: 'border-l-stone-500',
        swatch: 'bg-stone-500'
    },
    green: {
        dot: 'bg-green-500',
        note: 'border-l-green-500',
        swatch: 'bg-green-500'
    },
    blue: {
        dot: 'bg-blue-500',
        note: 'border-l-blue-500',
        swatch: 'bg-blue-500'
    },
    purple: {
        dot: 'bg-purple-500',
        note: 'border-l-purple-500',
        swatch: 'bg-purple-500'
    }
};