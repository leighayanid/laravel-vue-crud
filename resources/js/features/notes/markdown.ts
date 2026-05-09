function escapeHtml(value: string) {
    return value
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function inlineMarkdown(value: string) {
    return value
        .replace(/`([^`]+)`/g, '<code>$1</code>')
        .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
        .replace(/\*([^*]+)\*/g, '<em>$1</em>')
        .replace(
            /\[([^\]]+)]\((https?:\/\/[^\s)]+)\)/g,
            '<a href="$2" target="_blank" rel="noreferrer">$1</a>',
        );
}

export function markdownToHtml(markdown: string) {
    const lines = escapeHtml(markdown).split('\n');
    const html: string[] = [];
    let inList = false;
    let inCode = false;

    for (const rawLine of lines) {
        const line = rawLine.trimEnd();
        const trimmed = line.trim();

        if (trimmed.startsWith('```')) {
            html.push(inCode ? '</code></pre>' : '<pre><code>');
            inCode = !inCode;
            continue;
        }

        if (inCode) {
            html.push(`${line}\n`);
            continue;
        }

        if (!trimmed) {
            if (inList) {
                html.push('</ul>');
                inList = false;
            }

            continue;
        }

        const listMatch = trimmed.match(/^[-*]\s+(.+)/);

        if (listMatch) {
            if (!inList) {
                html.push('<ul>');
                inList = true;
            }

            html.push(`<li>${inlineMarkdown(listMatch[1])}</li>`);
            continue;
        }

        if (inList) {
            html.push('</ul>');
            inList = false;
        }

        if (trimmed.startsWith('### ')) {
            html.push(`<h3>${inlineMarkdown(trimmed.slice(4))}</h3>`);
        } else if (trimmed.startsWith('## ')) {
            html.push(`<h2>${inlineMarkdown(trimmed.slice(3))}</h2>`);
        } else if (trimmed.startsWith('# ')) {
            html.push(`<h1>${inlineMarkdown(trimmed.slice(2))}</h1>`);
        } else if (trimmed.startsWith('> ')) {
            html.push(
                `<blockquote>${inlineMarkdown(trimmed.slice(2))}</blockquote>`,
            );
        } else {
            html.push(`<p>${inlineMarkdown(trimmed)}</p>`);
        }
    }

    if (inList) {
        html.push('</ul>');
    }

    if (inCode) {
        html.push('</code></pre>');
    }

    return html.join('');
}