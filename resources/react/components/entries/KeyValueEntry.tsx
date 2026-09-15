import { Copy } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { useNotification } from '@laravilt/notifications/composables/useNotification';
import { formatStateValue } from '../../lib/entries';

export interface KeyValueEntryProps {
    label: string;
    state: any;
    placeholder?: string;
    keyLabel?: string;
    valueLabel?: string;
    copyableKeys?: boolean;
    copyableValues?: boolean;
    /** Vue fall-through `class`. */
    className?: string;
}

export default function KeyValueEntry({
    label,
    state,
    placeholder = '—',
    keyLabel = 'Key',
    valueLabel = 'Value',
    copyableKeys = false,
    copyableValues = false,
    className,
}: KeyValueEntryProps) {
    const { notify } = useNotification();

    const entries: { key: string; value: string }[] =
        !state || typeof state !== 'object'
            ? []
            : Object.entries(state).map(([key, value]) => ({
                  key,
                  // Nested objects / arrays as JSON / joined text instead of "[object Object]"
                  value: formatStateValue(value),
              }));

    const handleCopyKey = (key: string) => {
        navigator.clipboard.writeText(key);
        notify({
            title: 'Copied',
            body: 'Key copied to clipboard',
            type: 'success',
        });
    };

    const handleCopyValue = (value: string) => {
        navigator.clipboard.writeText(value);
        notify({
            title: 'Copied',
            body: 'Value copied to clipboard',
            type: 'success',
        });
    };

    return (
        <div className={cn('flex flex-col gap-1', className)}>
            <div className="text-sm font-medium text-foreground">{label}</div>
            {entries.length > 0 ? (
                <div className="rounded-md border border-border overflow-hidden">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50">
                            <tr>
                                <th className="px-4 py-2 text-left font-medium text-foreground">{keyLabel}</th>
                                <th className="px-4 py-2 text-left font-medium text-foreground">{valueLabel}</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {entries.map((entry, index) => (
                                <tr key={index} className="hover:bg-muted/30">
                                    <td className="px-4 py-2">
                                        <div className="flex items-center gap-2">
                                            <span className="font-medium text-foreground">{entry.key}</span>
                                            {copyableKeys && (
                                                <Button
                                                    variant="ghost"
                                                    size="icon"
                                                    className="h-6 w-6 shrink-0"
                                                    onClick={() => handleCopyKey(entry.key)}
                                                >
                                                    <Copy className="h-3 w-3" />
                                                </Button>
                                            )}
                                        </div>
                                    </td>
                                    <td className="px-4 py-2">
                                        <div className="flex items-center gap-2">
                                            <span className="text-muted-foreground">{entry.value}</span>
                                            {copyableValues && (
                                                <Button
                                                    variant="ghost"
                                                    size="icon"
                                                    className="h-6 w-6 shrink-0"
                                                    onClick={() => handleCopyValue(entry.value)}
                                                >
                                                    <Copy className="h-3 w-3" />
                                                </Button>
                                            )}
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            ) : (
                <span className="text-sm text-muted-foreground italic">{placeholder}</span>
            )}
        </div>
    );
}

export { KeyValueEntry };
