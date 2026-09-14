import { Copy } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { useNotification } from '@laravilt/notifications/composables/useNotification';
import { resolveIcon } from '@laravilt/support/lib/icons';
import { toDisplayString } from '../../lib/toDisplayString';

export interface TextEntryProps {
    label: string;
    state: any;
    placeholder?: string;
    copyable?: boolean;
    limit?: number | null;
    wrap?: boolean;
    markdown?: boolean;
    html?: boolean;
    prefix?: string | null;
    suffix?: string | null;
    badge?: boolean;
    color?: string | null;
    icon?: string | null;
    strikethrough?: boolean;
    /** Vue fall-through `class` (InfoList passes the column-span classes). */
    className?: string;
}

const badgeColorMap: Record<string, string> = {
    primary: 'default',
    success: 'success',
    danger: 'destructive',
    warning: 'warning',
    info: 'secondary',
    gray: 'secondary',
    secondary: 'secondary',
};

export default function TextEntry({
    label,
    state,
    placeholder = '-',
    copyable = false,
    limit = null,
    wrap = false,
    html = false,
    prefix = null,
    suffix = null,
    badge = false,
    color = null,
    icon = null,
    strikethrough = false,
    className,
}: TextEntryProps) {
    const { notify } = useNotification();

    const formattedValue = (() => {
        if (state === null || state === undefined) {
            return placeholder;
        }

        let result = String(state);

        // Apply character limit
        if (limit && result.length > limit) {
            result = result.substring(0, limit) + '...';
        }

        // Add prefix and suffix
        if (prefix) {
            result = prefix + result;
        }

        if (suffix) {
            result = result + suffix;
        }

        return result;
    })();

    const isArray = Array.isArray(state);

    const LucideIconComponent = icon ? resolveIcon(icon) : null;

    // 'success' / 'warning' are not variants of the kit Badge (nor of the Vue one) — cva then applies only the base classes.
    const badgeVariant = (color ? badgeColorMap[color] || 'secondary' : 'secondary') as any;

    const handleCopy = () => {
        if (copyable && formattedValue && formattedValue !== placeholder) {
            navigator.clipboard.writeText(String(state));
            notify({
                title: 'Copied',
                body: 'Copied to clipboard',
                type: 'success',
            });
        }
    };

    let content;

    if (badge && isArray) {
        /* Multiple badges for array values */
        content = (
            <div className="flex flex-wrap gap-1.5">
                {(state as any[]).map((item, index) => (
                    <Badge key={index} variant={badgeVariant} className="font-normal flex items-center gap-1.5">
                        {LucideIconComponent && <LucideIconComponent className="h-3 w-3 shrink-0" />}
                        {toDisplayString(item)}
                    </Badge>
                ))}
            </div>
        );
    } else if (badge) {
        /* Single badge */
        content = (
            <Badge variant={badgeVariant} className="font-normal flex items-center gap-1.5">
                {LucideIconComponent && <LucideIconComponent className="h-3 w-3 shrink-0" />}
                {formattedValue}
            </Badge>
        );
    } else if (html) {
        content = (
            <div
                className={cn('text-sm text-muted-foreground', wrap ? 'whitespace-normal' : 'truncate', strikethrough ? 'line-through' : '')}
                dangerouslySetInnerHTML={{ __html: formattedValue }}
            />
        );
    } else {
        content = (
            <span
                className={cn(
                    'text-sm',
                    state === null || state === undefined ? 'text-muted-foreground italic' : 'text-foreground',
                    wrap ? 'whitespace-normal' : 'truncate',
                    strikethrough ? 'line-through' : '',
                )}
            >
                {formattedValue}
            </span>
        );
    }

    return (
        <div className={cn('flex flex-col gap-1', className)}>
            <div className="text-sm font-medium text-foreground">{label}</div>
            <div className="flex items-start gap-2">
                {/* Icon (outside badge) */}
                {LucideIconComponent && !badge && <LucideIconComponent className="h-4 w-4 shrink-0 mt-0.5 text-muted-foreground" />}

                {content}

                {copyable && state !== null && state !== undefined && (
                    <Button variant="ghost" size="icon" className="h-6 w-6 shrink-0" onClick={handleCopy}>
                        <Copy className="h-3 w-3" />
                    </Button>
                )}
            </div>
        </div>
    );
}

export { TextEntry };
