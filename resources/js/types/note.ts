export type Note = {
    id: number;
    title: string;
    content: string;
    excerpt: string;
    color: NoteColor;
    is_pinned: boolean;
    updated_at: string | null;
    updated_at_human: string | null;
};

export type NoteColor = 'slate' | 'sky' | 'emerald' | 'amber' | 'rose';
