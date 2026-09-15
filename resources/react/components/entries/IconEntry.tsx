import { cn } from '@/lib/utils';
import { resolveIcon } from '@laravilt/support/lib/icons';
import { isEmptyState } from '../../lib/entries';

export interface IconEntryProps {
    label: string;
    state: any;
    placeholder?: string;
    size?: string | null;
    circular?: boolean;
    iconColor?: string | null;
    /** Vue fall-through `class`. */
    className?: string;
}

const getSizeClass = (size: string | null): string => {
    switch (size) {
        case 'xs':
            return 'h-3 w-3';
        case 'sm':
            return 'h-4 w-4';
        case 'md':
            return 'h-5 w-5';
        case 'lg':
            return 'h-6 w-6';
        case 'xl':
            return 'h-8 w-8';
        default:
            return 'h-5 w-5';
    }
};

const colorMap: Record<string, string> = {
    primary: 'text-primary',
    success: 'text-green-600',
    danger: 'text-red-600',
    warning: 'text-yellow-600',
    info: 'text-blue-600',
    gray: 'text-gray-600',
};

export default function IconEntry({ label, state, placeholder = '—', size = null, circular = false, iconColor = null, className }: IconEntryProps) {
    // Ensure state is a string
    const LucideIconComponent = isEmptyState(state) || typeof state === 'object' ? null : resolveIcon(String(state));

    const sizeClass = getSizeClass(size);

    const colorClass = iconColor ? colorMap[iconColor] || 'text-foreground' : 'text-foreground';

    return (
        <div className={cn('flex flex-col gap-1', className)}>
            <div className="text-sm font-medium text-foreground">{label}</div>
            <div className="flex items-center">
                {LucideIconComponent ? (
                    <div className={cn('inline-flex items-center justify-center', circular && 'rounded-full bg-muted p-2')}>
                        <LucideIconComponent className={cn(sizeClass, colorClass)} />
                    </div>
                ) : (
                    <span className="text-sm text-muted-foreground italic">{placeholder}</span>
                )}
            </div>
        </div>
    );
}

export { IconEntry };
