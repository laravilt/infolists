import { ChevronDown, ChevronRight } from 'lucide-react';
import { useState, type ComponentType } from 'react';
import { cn } from '@/lib/utils';
import BadgeEntry from './BadgeEntry';
import CodeEntry from './CodeEntry';
import ColorEntry from './ColorEntry';
import IconEntry from './IconEntry';
import ImageEntry from './ImageEntry';
import KeyValueEntry from './KeyValueEntry';
import TextEntry from './TextEntry';

export interface RepeatableEntryProps {
    label: string;
    state: any[];
    placeholder?: string;
    schema?: any[];
    collapsible?: boolean;
    collapsed?: boolean;
    emptyMessage?: string;
    /** Vue fall-through `class`. */
    className?: string;
}

const EMPTY: any[] = [];

const componentMap: Record<string, ComponentType<any>> = {
    TextEntry,
    IconEntry,
    ImageEntry,
    ColorEntry,
    CodeEntry,
    KeyValueEntry,
    BadgeEntry,
};

const getEntryComponent = (componentType: string): ComponentType<any> => {
    return componentMap[componentType] || TextEntry;
};

export default function RepeatableEntry({
    label,
    state,
    schema = EMPTY,
    collapsible = false,
    collapsed = false,
    emptyMessage = 'No items',
    className,
}: RepeatableEntryProps) {
    // If collapsed by default, start with all items collapsed (evaluated once, like the Vue setup)
    const [expandedItems, setExpandedItems] = useState<Set<number>>(() => {
        const initial = new Set<number>();

        if (!collapsed && Array.isArray(state)) {
            state.forEach((_, index) => {
                initial.add(index);
            });
        }

        return initial;
    });

    const toggleItem = (index: number) => {
        setExpandedItems((current) => {
            const next = new Set(current);

            if (next.has(index)) {
                next.delete(index);
            } else {
                next.add(index);
            }

            return next;
        });
    };

    const isExpanded = (index: number) => {
        return expandedItems.has(index);
    };

    const items: any[] = Array.isArray(state) ? state : [];

    return (
        <div className={cn('flex flex-col gap-1', className)}>
            <div className="text-sm font-medium text-foreground">{label}</div>
            {items.length > 0 ? (
                <div className="flex flex-col gap-2">
                    {items.map((item, index) => {
                        const Chevron = isExpanded(index) ? ChevronDown : ChevronRight;

                        return (
                            <div key={index} className="rounded-md border border-border overflow-hidden">
                                {collapsible && (
                                    <div
                                        className="flex items-center justify-between px-4 py-3 bg-muted/50 cursor-pointer hover:bg-muted"
                                        onClick={() => toggleItem(index)}
                                    >
                                        <span className="text-sm font-medium text-foreground">Item {index + 1}</span>
                                        <Chevron className="h-4 w-4 text-muted-foreground" />
                                    </div>
                                )}
                                {(!collapsible || isExpanded(index)) && (
                                    <div className="p-4 space-y-4">
                                        {schema.map((entry, entryIndex) => {
                                            const Entry = getEntryComponent(entry.component);

                                            return <Entry key={entryIndex} {...entry} state={item[entry.name]} />;
                                        })}
                                    </div>
                                )}
                            </div>
                        );
                    })}
                </div>
            ) : (
                <span className="text-sm text-muted-foreground italic">{emptyMessage}</span>
            )}
        </div>
    );
}

export { RepeatableEntry };
