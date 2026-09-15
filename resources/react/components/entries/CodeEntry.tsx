import { Copy } from 'lucide-react';
import type { CSSProperties } from 'react';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { useNotification } from '@laravilt/notifications/composables/useNotification';
import { toDisplayString } from '../../lib/toDisplayString';

export interface CodeEntryProps {
    label: string;
    state: any;
    placeholder?: string;
    copyable?: boolean;
    language?: string;
    maxHeight?: number | null;
    lineNumbers?: boolean;
    /** Vue fall-through `class`. */
    className?: string;
}

export default function CodeEntry({
    label,
    state,
    placeholder = '—',
    copyable = true,
    maxHeight = null,
    lineNumbers = true,
    className,
}: CodeEntryProps) {
    const { notify } = useNotification();

    const codeStyle: CSSProperties = {};

    if (maxHeight) {
        codeStyle.maxHeight = `${maxHeight}px`;
        codeStyle.overflow = 'auto';
    }

    const codeLines: string[] = state ? String(state).split('\n') : [];

    const handleCopy = () => {
        if (copyable && state) {
            navigator.clipboard.writeText(state);
            notify({
                title: 'Copied',
                body: 'Code copied to clipboard',
                type: 'success',
            });
        }
    };

    return (
        <div className={cn('flex flex-col gap-1', className)}>
            <div className="flex items-center justify-between">
                <div className="text-sm font-medium text-foreground">{label}</div>
                {copyable && state && (
                    <Button variant="ghost" size="icon" className="h-6 w-6 shrink-0" onClick={handleCopy}>
                        <Copy className="h-3 w-3" />
                    </Button>
                )}
            </div>
            {state ? (
                <div className="rounded-md border border-border bg-muted/30 overflow-hidden" style={codeStyle}>
                    <pre className="p-4 text-sm overflow-x-auto">
                        <code className="font-mono">
                            {lineNumbers
                                ? codeLines.map((line, index) => (
                                      <span key={index} className="block">
                                          <span className="inline-block w-8 text-muted-foreground select-none text-right mr-4">
                                              {index + 1}
                                          </span>
                                          <span>{line}</span>
                                      </span>
                                  ))
                                : toDisplayString(state)}
                        </code>
                    </pre>
                </div>
            ) : (
                <span className="text-sm text-muted-foreground italic">{placeholder}</span>
            )}
        </div>
    );
}

export { CodeEntry };
