import { Copy } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { useNotification } from '@laravilt/notifications/composables/useNotification';
import { formatStateValue, isEmptyState } from '../../lib/entries';

export interface ColorEntryProps {
    label: string;
    state: any;
    placeholder?: string;
    copyable?: boolean;
    showLabel?: boolean;
    size?: string | null;
    /** Vue fall-through `class`. */
    className?: string;
}

const getSizeClass = (size: string | null): string => {
    switch (size) {
        case 'xs':
            return 'h-4 w-4';
        case 'sm':
            return 'h-6 w-6';
        case 'md':
            return 'h-8 w-8';
        case 'lg':
            return 'h-10 w-10';
        case 'xl':
            return 'h-12 w-12';
        default:
            return 'h-8 w-8';
    }
};

export default function ColorEntry({
    label,
    state,
    placeholder = '—',
    copyable = true,
    showLabel = true,
    size = null,
    className,
}: ColorEntryProps) {
    const { notify } = useNotification();

    const sizeClass = getSizeClass(size);

    const handleCopy = () => {
        if (copyable && !isEmptyState(state)) {
            navigator.clipboard.writeText(formatStateValue(state));
            notify({
                title: 'Copied',
                body: 'Color copied to clipboard',
                type: 'success',
            });
        }
    };

    return (
        <div className={cn('flex flex-col gap-1', className)}>
            <div className="text-sm font-medium text-foreground">{label}</div>
            {!isEmptyState(state) ? (
                <div className="flex items-center gap-2">
                    <div className={cn(sizeClass, 'rounded border border-border shrink-0')} style={{ backgroundColor: formatStateValue(state) }} />
                    {showLabel && <span className="text-sm text-foreground font-mono">{formatStateValue(state)}</span>}
                    {copyable && (
                        <Button variant="ghost" size="icon" className="h-6 w-6 shrink-0" onClick={handleCopy}>
                            <Copy className="h-3 w-3" />
                        </Button>
                    )}
                </div>
            ) : (
                <span className="text-sm text-muted-foreground italic">{placeholder}</span>
            )}
        </div>
    );
}

export { ColorEntry };
