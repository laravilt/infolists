import { Badge } from '@/components/ui/badge';
import { cn } from '@/lib/utils';
import { resolveIcon } from '@laravilt/support/lib/icons';
import { toDisplayString } from '../../lib/toDisplayString';

export interface BadgeEntryProps {
    label: string;
    state: any;
    placeholder?: string;
    color?: string | null;
    icon?: string | null;
    colors?: Record<string, string>;
    icons?: Record<string, string>;
    /** Vue fall-through `class`. */
    className?: string;
}

const EMPTY: Record<string, string> = {};

const badgeColorMap: Record<string, string> = {
    primary: 'default',
    success: 'success',
    danger: 'destructive',
    warning: 'warning',
    info: 'secondary',
    gray: 'secondary',
    secondary: 'secondary',
};

export default function BadgeEntry({
    label,
    state,
    placeholder = '—',
    color = null,
    icon = null,
    colors = EMPTY,
    icons = EMPTY,
    className,
}: BadgeEntryProps) {
    const currentColor = colors && state && colors[state] ? colors[state] : color || 'secondary';

    const currentIcon = icons && state && icons[state] ? icons[state] : icon;

    const LucideIconComponent = currentIcon ? resolveIcon(currentIcon) : null;

    // 'success' / 'warning' are not Badge variants — cva then applies only the base classes (same as Vue).
    const badgeVariant = (badgeColorMap[currentColor] || 'secondary') as any;

    return (
        <div className={cn('flex flex-col gap-1', className)}>
            <div className="text-sm font-medium text-foreground">{label}</div>
            <div className="flex items-center">
                {state ? (
                    <Badge variant={badgeVariant} className="font-normal flex items-center gap-1.5">
                        {LucideIconComponent && <LucideIconComponent className="h-3 w-3 shrink-0" />}
                        {toDisplayString(state)}
                    </Badge>
                ) : (
                    <span className="text-sm text-muted-foreground italic">{placeholder}</span>
                )}
            </div>
        </div>
    );
}

export { BadgeEntry };
